<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Request;
use App\Core\Response;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;

class SitemapController
{
    /**
     * Sitemap index -- points to the individual sub-sitemaps.
     */
    public function index(Request $request, array $params): void
    {
        $baseUrl = rtrim(env('APP_URL', ''), '/');
        $now     = date('c');

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $sitemaps = [
            'sitemap-posts.xml',
            'sitemap-categories.xml',
            'sitemap-pages.xml',
        ];

        foreach ($sitemaps as $map) {
            $xml .= "  <sitemap>\n";
            $xml .= '    <loc>' . $this->xmlEscape($baseUrl . '/' . $map) . "</loc>\n";
            $xml .= '    <lastmod>' . $now . "</lastmod>\n";
            $xml .= "  </sitemap>\n";
        }

        $xml .= "</sitemapindex>\n";

        Response::setHeader('Content-Type', 'application/xml; charset=utf-8');
        Response::xml($xml);
    }

    /**
     * Sitemap for all published posts.
     */
    public function posts(Request $request, array $params): void
    {
        $baseUrl = rtrim(env('APP_URL', ''), '/');

        // Fetch all published posts (no pagination limit for sitemap)
        $total = Post::countPublished();
        $posts = Post::published($total ?: 1, 0);

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Home page
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . $this->xmlEscape($baseUrl . '/') . "</loc>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        $xml .= "  </url>\n";

        foreach ($posts as $post) {
            $lastmod = date('c', strtotime($post['updated_at'] ?? $post['published_at']));

            $xml .= "  <url>\n";
            $xml .= '    <loc>' . $this->xmlEscape($baseUrl . '/' . $post['slug']) . "</loc>\n";
            $xml .= '    <lastmod>' . $lastmod . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>\n";

        Response::setHeader('Content-Type', 'application/xml; charset=utf-8');
        Response::xml($xml);
    }

    /**
     * Sitemap for all categories.
     */
    public function categories(Request $request, array $params): void
    {
        $baseUrl    = rtrim(env('APP_URL', ''), '/');
        $categories = Category::all();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($categories as $category) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . $this->xmlEscape($baseUrl . '/category/' . $category['slug']) . "</loc>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.6</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>\n";

        Response::setHeader('Content-Type', 'application/xml; charset=utf-8');
        Response::xml($xml);
    }

    /**
     * Sitemap for all published pages.
     */
    public function pages(Request $request, array $params): void
    {
        $baseUrl = rtrim(env('APP_URL', ''), '/');
        $pages   = Page::published();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($pages as $page) {
            $lastmod = !empty($page['updated_at'])
                ? date('c', strtotime($page['updated_at']))
                : date('c', strtotime($page['created_at'] ?? 'now'));

            $xml .= "  <url>\n";
            $xml .= '    <loc>' . $this->xmlEscape($baseUrl . '/' . $page['slug']) . "</loc>\n";
            $xml .= '    <lastmod>' . $lastmod . "</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.5</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>\n";

        Response::setHeader('Content-Type', 'application/xml; charset=utf-8');
        Response::xml($xml);
    }

    /**
     * Serve the robots.txt content from database settings.
     */
    public function robots(Request $request, array $params): void
    {
        $baseUrl   = rtrim(env('APP_URL', ''), '/');
        $robotsTxt = Setting::get('robots_txt', '');

        // Provide sensible defaults if no custom robots.txt is stored
        if (empty(trim($robotsTxt))) {
            $robotsTxt  = "User-agent: *\n";
            $robotsTxt .= "Allow: /\n";
            $robotsTxt .= "\n";
            $robotsTxt .= "Sitemap: {$baseUrl}/sitemap.xml\n";
        }

        Response::setHeader('Content-Type', 'text/plain; charset=utf-8');
        echo $robotsTxt;
        exit;
    }

    /**
     * Escape a string for safe inclusion in XML.
     */
    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
