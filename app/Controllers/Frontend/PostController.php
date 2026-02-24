<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Request;
use App\Core\Response;
use App\Core\Sanitizer;
use App\Core\SEO;
use App\Core\View;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class PostController
{
    /**
     * Display a single published post by its slug.
     * Falls back to PageController if no matching post is found.
     */
    public function show(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $post = Post::findBySlug($slug);

        if (!$post || $post['status'] !== 'published') {
            // Delegate to PageController for static pages
            (new PageController())->show($request, $params);
            return;
        }

        // Track page view
        Post::incrementViews((int) $post['id']);

        // Related data
        $author   = User::find((int) $post['user_id']);
        $category = !empty($post['category_id']) ? Category::find((int) $post['category_id']) : null;
        $tags     = Post::getTags((int) $post['id']);
        $related  = Post::related((int) $post['id'], $category ? (int) $category['id'] : null);
        $comments = Comment::byPost((int) $post['id']);

        // Table of contents from H2/H3 headings — also injects IDs into content
        $toc = [];
        $postContent = $this->injectHeadingIds($post['content'] ?? '', $toc);
        $post['content'] = $postContent;

        // SEO
        $description = !empty($post['excerpt'])
            ? $post['excerpt']
            : Sanitizer::excerpt($post['content'] ?? '', 160);

        $seo = new SEO();
        $seo->setTitle($post['title'])
            ->setDescription($description)
            ->setCanonical(url($post['slug']))
            ->setType('article');

        if (!empty($post['featured_image'])) {
            $seo->setImage(upload_url($post['featured_image']));
        }

        $tagNames = array_map(fn(array $t) => $t['name'], $tags);

        $seo->setArticleMeta(
            $post['published_at'],
            $post['updated_at'],
            url('author/' . ($author['slug'] ?? '')),
            $category['name'] ?? 'Uncategorized',
            $tagNames,
        );

        // Article structured data
        if ($author) {
            $seo->setStructuredData(SEO::articleSchema($post, $author));
        }

        // Breadcrumbs: Home > Category > Title
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
        ];
        if ($category) {
            $breadcrumbs[] = ['name' => $category['name'], 'url' => url('category/' . $category['slug'])];
        }
        $breadcrumbs[] = ['name' => $post['title'], 'url' => url($post['slug'])];
        $seo->setBreadcrumbs($breadcrumbs);

        // Render
        $view = new View();
        $view->setLayout('layouts/main');
        echo $view->render('frontend/post', [
            'pageTitle'   => $post['title'],
            'post'        => $post,
            'author'      => $author,
            'category'    => $category,
            'tags'        => $tags,
            'related'     => $related,
            'comments'    => $comments,
            'toc'         => $toc,
            'seo'         => $seo,
        ]);
    }

    /**
     * Parse H2 and H3 headings from HTML content, inject id attributes into
     * the headings, and populate a table-of-contents array by reference.
     *
     * Each TOC entry: ['id' => 'slug', 'text' => 'Heading text', 'level' => 2|3]
     *
     * @param string $html Raw post HTML content
     * @param array  $toc  Passed by reference; populated with TOC entries
     * @return string       HTML content with id attributes injected into headings
     */
    private function injectHeadingIds(string $html, array &$toc): string
    {
        if (empty(trim($html))) {
            return $html;
        }

        $usedIds = [];

        $result = preg_replace_callback(
            '/<h([23])([^>]*)>(.*?)<\/h([23])>/is',
            function ($match) use (&$toc, &$usedIds) {
                $level      = (int) $match[1];
                $attributes = $match[2];
                $innerHtml  = $match[3];
                $text       = strip_tags($innerHtml);

                // Check if there's already an id attribute
                $existingId = '';
                if (preg_match('/id=["\']([^"\']*)["\']/', $attributes, $idMatch)) {
                    $existingId = $idMatch[1];
                }

                $id = $existingId ?: $this->slugify($text);

                // Ensure uniqueness
                $baseId  = $id;
                $counter = 2;
                while (in_array($id, $usedIds, true)) {
                    $id = $baseId . '-' . $counter++;
                }
                $usedIds[] = $id;

                $toc[] = [
                    'id'    => $id,
                    'text'  => $text,
                    'level' => $level,
                ];

                // If heading already has an id, replace it; otherwise inject one
                if ($existingId) {
                    $newAttributes = preg_replace('/id=["\'][^"\']*["\']/', 'id="' . htmlspecialchars($id, ENT_QUOTES) . '"', $attributes);
                } else {
                    $newAttributes = ' id="' . htmlspecialchars($id, ENT_QUOTES) . '"' . $attributes;
                }

                return '<h' . $level . $newAttributes . '>' . $innerHtml . '</h' . $level . '>';
            },
            $html
        );

        return $result ?? $html;
    }

    /**
     * Convert a string into a URL-safe slug for heading anchors.
     */
    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s\-]/', '', $text);
        $text = preg_replace('/[\s\-]+/', '-', $text);
        return trim($text, '-');
    }
}
