<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Request;
use App\Core\Response;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;

class FeedController
{
    /**
     * Output an RSS 2.0 feed of the latest published posts.
     */
    public function rss(Request $request, array $params): void
    {
        $posts    = Post::published(20, 0);
        $siteName = Setting::get('site_name', env('APP_NAME', 'NewsCMS'));
        $tagline  = Setting::get('site_tagline', '');
        $baseUrl  = rtrim(env('APP_URL', ''), '/');

        $xml = $this->buildRssXml($posts, $siteName, $tagline, $baseUrl);

        Response::setHeader('Content-Type', 'application/rss+xml; charset=utf-8');
        Response::xml($xml);
    }

    /**
     * Output an RSS 2.0 feed filtered by category slug.
     */
    public function category(Request $request, array $params): void
    {
        $slug     = $params['slug'] ?? '';
        $category = Category::findBySlug($slug);

        if (!$category) {
            Response::notFound();
            return;
        }

        $posts    = Post::byCategory((int) $category['id'], 20, 0);
        $siteName = Setting::get('site_name', env('APP_NAME', 'NewsCMS'));
        $tagline  = $category['description'] ?? "Posts in {$category['name']}";
        $baseUrl  = rtrim(env('APP_URL', ''), '/');

        $feedTitle = "{$siteName} - {$category['name']}";

        $xml = $this->buildRssXml($posts, $feedTitle, $tagline, $baseUrl, $category['slug']);

        Response::setHeader('Content-Type', 'application/rss+xml; charset=utf-8');
        Response::xml($xml);
    }

    /**
     * Build a valid RSS 2.0 XML string from an array of posts.
     */
    private function buildRssXml(
        array $posts,
        string $title,
        string $description,
        string $baseUrl,
        ?string $categorySlug = null,
    ): string {
        $feedLink = $categorySlug
            ? $baseUrl . '/feed/category/' . $categorySlug
            : $baseUrl . '/feed';

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">' . "\n";
        $xml .= "<channel>\n";
        $xml .= '  <title>' . $this->xmlEscape($title) . "</title>\n";
        $xml .= '  <link>' . $this->xmlEscape($baseUrl) . "</link>\n";
        $xml .= '  <description>' . $this->xmlEscape($description) . "</description>\n";
        $xml .= '  <language>en-us</language>' . "\n";
        $xml .= '  <lastBuildDate>' . date('r') . "</lastBuildDate>\n";
        $xml .= '  <atom:link href="' . $this->xmlEscape($feedLink) . '" rel="self" type="application/rss+xml"/>' . "\n";

        foreach ($posts as $post) {
            $postUrl = $baseUrl . '/' . $post['slug'];
            $pubDate = date('r', strtotime($post['published_at']));
            $excerpt = !empty($post['excerpt']) ? $post['excerpt'] : mb_substr(strip_tags($post['content'] ?? ''), 0, 300);

            $xml .= "  <item>\n";
            $xml .= '    <title>' . $this->xmlEscape($post['title']) . "</title>\n";
            $xml .= '    <link>' . $this->xmlEscape($postUrl) . "</link>\n";
            $xml .= '    <guid isPermaLink="true">' . $this->xmlEscape($postUrl) . "</guid>\n";
            $xml .= '    <pubDate>' . $pubDate . "</pubDate>\n";
            $xml .= '    <description>' . $this->xmlEscape($excerpt) . "</description>\n";

            if (!empty($post['content'])) {
                $xml .= '    <content:encoded><![CDATA[' . $post['content'] . "]]></content:encoded>\n";
            }

            if (!empty($post['author_name'])) {
                $xml .= '    <author>' . $this->xmlEscape($post['author_name']) . "</author>\n";
            }

            if (!empty($post['category_name'])) {
                $xml .= '    <category>' . $this->xmlEscape($post['category_name']) . "</category>\n";
            }

            if (!empty($post['featured_image'])) {
                $imageUrl = rtrim(env('APP_URL', ''), '/') . '/uploads/' . ltrim($post['featured_image'], '/');
                $xml .= '    <enclosure url="' . $this->xmlEscape($imageUrl) . '" type="image/jpeg"/>' . "\n";
            }

            $xml .= "  </item>\n";
        }

        $xml .= "</channel>\n";
        $xml .= "</rss>\n";

        return $xml;
    }

    /**
     * Escape a string for safe inclusion in XML.
     */
    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
