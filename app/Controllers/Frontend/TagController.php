<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Core\SEO;
use App\Core\View;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;

class TagController
{
    /**
     * Display posts associated with a specific tag, with pagination.
     */
    public function index(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $tag  = Tag::findBySlug($slug);

        if (!$tag) {
            Response::notFound();
            return;
        }

        $currentPage = (int) ($params['page'] ?? $request->get('page', 1));
        $currentPage = max(1, $currentPage);
        $perPage     = (int) Setting::get('posts_per_page', 12);
        $total       = Post::countByTag((int) $tag['id']);

        $paginator = new Paginator($total, $perPage, $currentPage);
        $posts     = Post::byTag((int) $tag['id'], $paginator->perPage, $paginator->offset);

        // SEO
        $seo = new SEO();
        $seo->setTitle("Tag: {$tag['name']}")
            ->setDescription("Posts tagged with \"{$tag['name']}\".")
            ->setCanonical(url('tag/' . $tag['slug']));

        if ($paginator->hasPages()) {
            $prev = $paginator->hasPrev() ? url('tag/' . $tag['slug'] . '?page=' . $paginator->prevPage()) : null;
            $next = $paginator->hasNext() ? url('tag/' . $tag['slug'] . '?page=' . $paginator->nextPage()) : null;
            $seo->setPagination($prev, $next);
        }

        $seo->setBreadcrumbs([
            ['name' => 'Home',        'url' => url('/')],
            ['name' => $tag['name'],  'url' => url('tag/' . $tag['slug'])],
        ]);

        // Render
        $view = new View();
        $view->setLayout('layouts/main');
        echo $view->render('frontend/tag', [
            'pageTitle'   => "Tag: {$tag['name']}",
            'tag'         => $tag,
            'posts'       => $posts,
            'paginator'   => $paginator,
            'currentPage' => $currentPage,
            'seo'         => $seo,
        ]);
    }
}
