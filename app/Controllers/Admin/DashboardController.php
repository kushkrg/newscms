<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Request;
use App\Core\View;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class DashboardController
{
    /**
     * Gate: require an authenticated session for every action in this controller.
     */
    public function middleware(): void
    {
        Auth::requireAuth();
    }

    /**
     * Render the main admin dashboard with aggregated statistics.
     */
    public function index(Request $request, array $params): void
    {
        // ---- Aggregate counts ----
        $totalPosts      = (int) Database::query("SELECT COUNT(*) AS total FROM posts")->fetch()['total'];
        $publishedPosts  = Post::countPublished();
        $totalComments   = Comment::adminCount();
        $pendingComments = Comment::pendingCount();
        $totalUsers      = User::count();

        // ---- Recent content ----
        $recentPosts    = Post::adminList([], 5, 0);
        $recentComments = Comment::adminList('pending', 5, 0);

        // ---- Render ----
        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/dashboard', [
            'pageTitle'       => 'Dashboard',
            'currentPage'     => 'dashboard',
            'totalPosts'      => $totalPosts,
            'publishedPosts'  => $publishedPosts,
            'totalComments'   => $totalComments,
            'pendingComments' => $pendingComments,
            'totalUsers'      => $totalUsers,
            'recentPosts'     => $recentPosts,
            'recentComments'  => $recentComments,
        ]);
    }
}
