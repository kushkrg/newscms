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
use App\Models\User;

class AuthorController
{
    /**
     * Display posts written by a specific author, with pagination.
     */
    public function index(Request $request, array $params): void
    {
        $slug   = $params['slug'] ?? '';
        $author = User::findBySlug($slug);

        if (!$author) {
            Response::notFound();
            return;
        }

        $currentPage = (int) ($params['page'] ?? $request->get('page', 1));
        $currentPage = max(1, $currentPage);
        $perPage     = (int) Setting::get('posts_per_page', 12);
        $total       = Post::countByAuthor((int) $author['id']);

        $paginator = new Paginator($total, $perPage, $currentPage);
        $posts     = Post::byAuthor((int) $author['id'], $paginator->perPage, $paginator->offset);

        // SEO
        $seo = new SEO();
        $seo->setTitle("Posts by {$author['name']}")
            ->setDescription("All articles written by {$author['name']}.")
            ->setCanonical(url('author/' . $author['slug']));

        if (!empty($author['avatar'])) {
            $seo->setImage(upload_url($author['avatar']));
        }

        if ($paginator->hasPages()) {
            $prev = $paginator->hasPrev() ? url('author/' . $author['slug'] . '?page=' . $paginator->prevPage()) : null;
            $next = $paginator->hasNext() ? url('author/' . $author['slug'] . '?page=' . $paginator->nextPage()) : null;
            $seo->setPagination($prev, $next);
        }

        $seo->setBreadcrumbs([
            ['name' => 'Home',           'url' => url('/')],
            ['name' => $author['name'],  'url' => url('author/' . $author['slug'])],
        ]);

        // Render
        $view = new View();
        $view->setLayout('layouts/main');
        echo $view->render('frontend/author', [
            'pageTitle'   => "Posts by {$author['name']}",
            'author'      => $author,
            'posts'       => $posts,
            'paginator'   => $paginator,
            'currentPage' => $currentPage,
            'seo'         => $seo,
        ]);
    }
}
