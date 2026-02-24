<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Paginator;
use App\Core\Request;
use App\Core\SEO;
use App\Core\View;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;

class HomeController
{
    /**
     * Display the public home / blog listing page with pagination.
     */
    public function index(Request $request, array $params): void
    {
        $currentPage = (int) ($params['page'] ?? $request->get('page', 1));
        $currentPage = max(1, $currentPage);

        $perPage = (int) Setting::get('posts_per_page', 12);
        $total   = Post::countPublished();

        $paginator = new Paginator($total, $perPage, $currentPage);

        // Featured post only on the first page
        $featured = ($currentPage === 1) ? Post::featured(1) : [];

        $posts      = Post::published($paginator->perPage, $paginator->offset);
        $categories = Category::allWithCount();
        $tags       = array_slice(Tag::all(), 0, 20);

        // SEO
        $siteName = Setting::get('site_name', env('APP_NAME', 'NewsCMS'));
        $tagline  = Setting::get('site_tagline', '');

        $seo = new SEO();
        $seo->setTitle($siteName, false)
            ->setDescription($tagline ?: $siteName)
            ->setCanonical(url('/'))
            ->setStructuredData(SEO::websiteSchema());

        if ($paginator->hasPages()) {
            $prev = $paginator->hasPrev() ? url('?page=' . $paginator->prevPage()) : null;
            $next = $paginator->hasNext() ? url('?page=' . $paginator->nextPage()) : null;
            $seo->setPagination($prev, $next);
        }

        // Render
        $view = new View();
        $view->setLayout('layouts/main');
        echo $view->render('frontend/home', [
            'pageTitle'   => $siteName,
            'featured'    => $featured,
            'posts'       => $posts,
            'categories'  => $categories,
            'tags'        => $tags,
            'paginator'   => $paginator,
            'currentPage' => $currentPage,
            'seo'         => $seo,
        ]);
    }
}
