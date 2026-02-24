<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Paginator;
use App\Core\Recaptcha;
use App\Core\Request;
use App\Core\Response;
use App\Core\SEO;
use App\Core\Session;
use App\Core\View;
use App\Models\Post;
use App\Models\Setting;

class SearchController
{
    /**
     * Display search results for a query string, with pagination.
     */
    public function index(Request $request, array $params): void
    {
        $query = trim((string) $request->get('q', ''));

        // reCAPTCHA v3 verification (only when a search is performed)
        if ($query !== '') {
            $recaptchaToken = (string) $request->get('g-recaptcha-response', '');
            if (!Recaptcha::verify($recaptchaToken, 'search')) {
                Session::flash('error', 'reCAPTCHA verification failed. Please try again.');
                Response::redirect(url('search'));
                return;
            }
        }

        // Empty query -- render the search page with no results
        if ($query === '') {
            $seo = new SEO();
            $seo->setTitle('Search')
                ->setDescription('Search articles')
                ->setCanonical(url('search'))
                ->setRobots('noindex, follow');

            $view = new View();
            $view->setLayout('layouts/main');
            echo $view->render('frontend/search', [
                'pageTitle'   => 'Search',
                'query'       => '',
                'posts'       => [],
                'paginator'   => null,
                'currentPage' => 1,
                'seo'         => $seo,
            ]);
            return;
        }

        $currentPage = (int) ($params['page'] ?? $request->get('page', 1));
        $currentPage = max(1, $currentPage);
        $perPage     = (int) Setting::get('posts_per_page', 12);
        $total       = Post::countSearch($query);

        $paginator = new Paginator($total, $perPage, $currentPage);
        $posts     = Post::search($query, $paginator->perPage, $paginator->offset);

        // SEO -- noindex search result pages
        $seo = new SEO();
        $seo->setTitle("Search results for \"{$query}\"")
            ->setDescription("Found {$total} results for \"{$query}\".")
            ->setCanonical(url('search?q=' . urlencode($query)))
            ->setRobots('noindex, follow');

        if ($paginator->hasPages()) {
            $base = 'search?q=' . urlencode($query);
            $prev = $paginator->hasPrev() ? url($base . '&page=' . $paginator->prevPage()) : null;
            $next = $paginator->hasNext() ? url($base . '&page=' . $paginator->nextPage()) : null;
            $seo->setPagination($prev, $next);
        }

        // Render
        $view = new View();
        $view->setLayout('layouts/main');
        echo $view->render('frontend/search', [
            'pageTitle'   => "Search: {$query}",
            'query'       => $query,
            'posts'       => $posts,
            'paginator'   => $paginator,
            'currentPage' => $currentPage,
            'seo'         => $seo,
        ]);
    }
}
