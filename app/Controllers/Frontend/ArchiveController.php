<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Database;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\SEO;
use App\Core\View;
use App\Models\Post;
use App\Models\Setting;

class ArchiveController
{
    /**
     * Display archived posts filtered by year and optionally by month.
     */
    public function index(Request $request, array $params): void
    {
        $year  = isset($params['year'])  ? (int) $params['year']  : null;
        $month = isset($params['month']) ? (int) $params['month'] : null;

        $currentPage = (int) ($params['page'] ?? $request->get('page', 1));
        $currentPage = max(1, $currentPage);
        $perPage     = (int) Setting::get('posts_per_page', 12);

        // Build archive-filtered query
        $posts = [];
        $total = 0;

        if ($year) {
            $whereClause = "p.status = 'published' AND p.published_at <= NOW() AND YEAR(p.published_at) = :year";
            $countWhere  = "status = 'published' AND published_at <= NOW() AND YEAR(published_at) = :year";
            $queryParams = ['year' => $year];

            if ($month) {
                $whereClause .= " AND MONTH(p.published_at) = :month";
                $countWhere  .= " AND MONTH(published_at) = :month";
                $queryParams['month'] = $month;
            }

            $total = (int) Database::query(
                "SELECT COUNT(*) AS total FROM posts WHERE {$countWhere}",
                $queryParams
            )->fetch()['total'];

            $paginator = new Paginator($total, $perPage, $currentPage);

            $queryParams['limit']  = $paginator->perPage;
            $queryParams['offset'] = $paginator->offset;

            $posts = Database::query(
                "SELECT p.*,
                        u.name  AS author_name,
                        u.slug  AS author_slug,
                        u.avatar AS author_avatar,
                        c.name  AS category_name,
                        c.slug  AS category_slug
                 FROM posts p
                 LEFT JOIN users u      ON u.id = p.user_id
                 LEFT JOIN categories c ON c.id = p.category_id
                 WHERE {$whereClause}
                 ORDER BY p.published_at DESC
                 LIMIT :limit OFFSET :offset",
                $queryParams
            )->fetchAll();
        } else {
            // No year filter -- show all published posts
            $total     = Post::countPublished();
            $paginator = new Paginator($total, $perPage, $currentPage);
            $posts     = Post::published($paginator->perPage, $paginator->offset);
        }

        // Full archive list for the sidebar
        $archiveList = Post::archive();

        // SEO
        $titleParts = ['Archive'];
        if ($year) {
            $titleParts[] = (string) $year;
        }
        if ($month) {
            $titleParts[] = date('F', mktime(0, 0, 0, $month, 1));
        }
        $title = implode(' - ', $titleParts);

        $canonicalPath = 'archive';
        if ($year) {
            $canonicalPath .= '/' . $year;
            if ($month) {
                $canonicalPath .= '/' . str_pad((string) $month, 2, '0', STR_PAD_LEFT);
            }
        }

        $seo = new SEO();
        $seo->setTitle($title)
            ->setDescription("Browse the article archive" . ($year ? " for {$year}" : '') . '.')
            ->setCanonical(url($canonicalPath));

        if ($paginator->hasPages()) {
            $prev = $paginator->hasPrev() ? url($canonicalPath . '?page=' . $paginator->prevPage()) : null;
            $next = $paginator->hasNext() ? url($canonicalPath . '?page=' . $paginator->nextPage()) : null;
            $seo->setPagination($prev, $next);
        }

        $breadcrumbs = [
            ['name' => 'Home',    'url' => url('/')],
            ['name' => 'Archive', 'url' => url('archive')],
        ];
        if ($year) {
            $breadcrumbs[] = ['name' => (string) $year, 'url' => url('archive/' . $year)];
        }
        if ($month) {
            $breadcrumbs[] = ['name' => date('F', mktime(0, 0, 0, $month, 1)), 'url' => url($canonicalPath)];
        }
        $seo->setBreadcrumbs($breadcrumbs);

        // Render
        $view = new View();
        $view->setLayout('layouts/main');
        echo $view->render('frontend/archive', [
            'pageTitle'   => $title,
            'year'        => $year,
            'month'       => $month,
            'posts'       => $posts,
            'archiveList' => $archiveList,
            'paginator'   => $paginator,
            'currentPage' => $currentPage,
            'seo'         => $seo,
        ]);
    }
}
