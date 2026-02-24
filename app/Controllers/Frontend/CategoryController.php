<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Core\SEO;
use App\Core\View;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;

class CategoryController
{
    /**
     * Display posts belonging to a specific category, with pagination.
     */
    public function index(Request $request, array $params): void
    {
        $slug     = $params['slug'] ?? '';
        $category = Category::findBySlug($slug);

        if (!$category) {
            Response::notFound();
            return;
        }

        $currentPage = (int) ($params['page'] ?? $request->get('page', 1));
        $currentPage = max(1, $currentPage);
        $perPage     = (int) Setting::get('posts_per_page', 12);
        $total       = Post::countByCategory((int) $category['id']);

        $paginator = new Paginator($total, $perPage, $currentPage);
        $posts     = Post::byCategory((int) $category['id'], $paginator->perPage, $paginator->offset);

        // SEO
        $seo = new SEO();
        $seo->setTitle($category['name'])
            ->setDescription($category['description'] ?? "Browse all posts in {$category['name']}.")
            ->setCanonical(url('category/' . $category['slug']));

        if ($paginator->hasPages()) {
            $prev = $paginator->hasPrev() ? url('category/' . $category['slug'] . '?page=' . $paginator->prevPage()) : null;
            $next = $paginator->hasNext() ? url('category/' . $category['slug'] . '?page=' . $paginator->nextPage()) : null;
            $seo->setPagination($prev, $next);
        }

        $seo->setBreadcrumbs([
            ['name' => 'Home',            'url' => url('/')],
            ['name' => $category['name'], 'url' => url('category/' . $category['slug'])],
        ]);

        // Render
        $view = new View();
        $view->setLayout('layouts/main');
        echo $view->render('frontend/category', [
            'pageTitle'   => $category['name'],
            'category'    => $category,
            'posts'       => $posts,
            'paginator'   => $paginator,
            'currentPage' => $currentPage,
            'seo'         => $seo,
        ]);
    }
}
