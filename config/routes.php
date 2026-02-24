<?php

declare(strict_types=1);

use App\Controllers\Frontend\HomeController;
use App\Controllers\Frontend\PostController;
use App\Controllers\Frontend\PageController;
use App\Controllers\Frontend\CategoryController;
use App\Controllers\Frontend\TagController;
use App\Controllers\Frontend\AuthorController;
use App\Controllers\Frontend\SearchController;
use App\Controllers\Frontend\ArchiveController;
use App\Controllers\Frontend\CommentController;
use App\Controllers\Frontend\FeedController;
use App\Controllers\Frontend\SitemapController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\PostController as AdminPostController;
use App\Controllers\Admin\PageController as AdminPageController;
use App\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Controllers\Admin\TagController as AdminTagController;
use App\Controllers\Admin\CommentController as AdminCommentController;
use App\Controllers\Admin\MediaController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\SettingController;
use App\Controllers\Admin\RedirectController;
use App\Controllers\Admin\AdController;
use App\Controllers\Admin\MessageController as AdminMessageController;
use App\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Controllers\Frontend\SubscribeController;
use App\Controllers\Frontend\ContactController;

/**
 * Route definitions for the News/Blog CMS.
 *
 * @var App\Core\Router $router
 */

// ============================================================
//  Frontend Routes
// ============================================================

// Home / Pagination
$router->get('/', [HomeController::class, 'index']);
$router->get('/page/{page}', [HomeController::class, 'index']);

// Single article (clean URL: /slug instead of /article/slug)

// Category listing
$router->get('/category/{slug}', [CategoryController::class, 'index']);
$router->get('/category/{slug}/page/{page}', [CategoryController::class, 'index']);

// Tag listing
$router->get('/tag/{slug}', [TagController::class, 'index']);
$router->get('/tag/{slug}/page/{page}', [TagController::class, 'index']);

// Author listing
$router->get('/author/{slug}', [AuthorController::class, 'index']);
$router->get('/author/{slug}/page/{page}', [AuthorController::class, 'index']);

// Search
$router->get('/search', [SearchController::class, 'index']);

// Archive
$router->get('/archive', [ArchiveController::class, 'index']);
$router->get('/archive/{year}', [ArchiveController::class, 'index']);
$router->get('/archive/{year}/{month}', [ArchiveController::class, 'index']);

// Comments
$router->post('/comments/store', [CommentController::class, 'store']);

// Newsletter subscription
$router->post('/newsletter/subscribe', [SubscribeController::class, 'store']);
$router->get('/newsletter/unsubscribe', [SubscribeController::class, 'unsubscribe']);

// Contact form
$router->get('/contact', [ContactController::class, 'show']);
$router->post('/contact', [ContactController::class, 'submit']);

// RSS feeds
$router->get('/feed', [FeedController::class, 'rss']);
$router->get('/feed/category/{slug}', [FeedController::class, 'category']);

// Sitemaps & robots
$router->get('/sitemap.xml', [SitemapController::class, 'index']);
$router->get('/post-sitemap.xml', [SitemapController::class, 'posts']);
$router->get('/category-sitemap.xml', [SitemapController::class, 'categories']);
$router->get('/page-sitemap.xml', [SitemapController::class, 'pages']);
$router->get('/robots.txt', [SitemapController::class, 'robots']);

// ============================================================
//  Admin Routes
// ============================================================

// Authentication
$router->get('/admin/login', [AuthController::class, 'loginForm']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->post('/admin/logout', [AuthController::class, 'logout']);

// Dashboard
$router->get('/admin', [DashboardController::class, 'index']);

// Posts
$router->get('/admin/posts', [AdminPostController::class, 'index']);
$router->get('/admin/posts/create', [AdminPostController::class, 'create']);
$router->post('/admin/posts/store', [AdminPostController::class, 'store']);
$router->get('/admin/posts/{id}/edit', [AdminPostController::class, 'edit']);
$router->post('/admin/posts/{id}/update', [AdminPostController::class, 'update']);
$router->post('/admin/posts/{id}/delete', [AdminPostController::class, 'delete']);

// Pages
$router->get('/admin/pages', [AdminPageController::class, 'index']);
$router->get('/admin/pages/create', [AdminPageController::class, 'create']);
$router->post('/admin/pages/store', [AdminPageController::class, 'store']);
$router->get('/admin/pages/{id}/edit', [AdminPageController::class, 'edit']);
$router->post('/admin/pages/{id}/update', [AdminPageController::class, 'update']);
$router->post('/admin/pages/{id}/delete', [AdminPageController::class, 'delete']);

// Categories
$router->get('/admin/categories', [AdminCategoryController::class, 'index']);
$router->post('/admin/categories/store', [AdminCategoryController::class, 'store']);
$router->get('/admin/categories/{id}/edit', [AdminCategoryController::class, 'edit']);
$router->post('/admin/categories/{id}/update', [AdminCategoryController::class, 'update']);
$router->post('/admin/categories/{id}/delete', [AdminCategoryController::class, 'delete']);

// Tags
$router->get('/admin/tags', [AdminTagController::class, 'index']);
$router->post('/admin/tags/store', [AdminTagController::class, 'store']);
$router->post('/admin/tags/{id}/delete', [AdminTagController::class, 'delete']);

// Comments
$router->get('/admin/comments', [AdminCommentController::class, 'index']);
$router->post('/admin/comments/{id}/approve', [AdminCommentController::class, 'approve']);
$router->post('/admin/comments/{id}/spam', [AdminCommentController::class, 'spam']);
$router->post('/admin/comments/{id}/delete', [AdminCommentController::class, 'delete']);

// Media
$router->get('/admin/media', [MediaController::class, 'index']);
$router->get('/admin/media/json', [MediaController::class, 'json']);
$router->post('/admin/media/upload', [MediaController::class, 'upload']);
$router->post('/admin/media/{id}/delete', [MediaController::class, 'delete']);

// Users
$router->get('/admin/users', [UserController::class, 'index']);
$router->get('/admin/users/create', [UserController::class, 'create']);
$router->post('/admin/users/store', [UserController::class, 'store']);
$router->get('/admin/users/{id}/edit', [UserController::class, 'edit']);
$router->post('/admin/users/{id}/update', [UserController::class, 'update']);
$router->post('/admin/users/{id}/delete', [UserController::class, 'delete']);

// Settings
$router->get('/admin/settings', [SettingController::class, 'index']);
$router->post('/admin/settings/save', [SettingController::class, 'save']);

// Redirects
$router->get('/admin/redirects', [RedirectController::class, 'index']);
$router->post('/admin/redirects/store', [RedirectController::class, 'store']);
$router->post('/admin/redirects/{id}/delete', [RedirectController::class, 'delete']);

// Ads
$router->get('/admin/ads', [AdController::class, 'index']);
$router->post('/admin/ads/save', [AdController::class, 'save']);

// Messages
$router->get('/admin/messages', [AdminMessageController::class, 'index']);
$router->get('/admin/messages/{id}', [AdminMessageController::class, 'show']);
$router->post('/admin/messages/{id}/reply', [AdminMessageController::class, 'reply']);
$router->post('/admin/messages/{id}/delete', [AdminMessageController::class, 'delete']);

// Subscribers
$router->get('/admin/subscribers', [AdminSubscriberController::class, 'index']);
$router->get('/admin/subscribers/compose', [AdminSubscriberController::class, 'compose']);
$router->post('/admin/subscribers/send', [AdminSubscriberController::class, 'send']);
$router->get('/admin/subscribers/email-config', [AdminSubscriberController::class, 'emailConfig']);
$router->post('/admin/subscribers/email-config/save', [AdminSubscriberController::class, 'saveEmailConfig']);
$router->get('/admin/subscribers/{id}/edit', [AdminSubscriberController::class, 'edit']);
$router->post('/admin/subscribers/{id}/update', [AdminSubscriberController::class, 'update']);
$router->post('/admin/subscribers/{id}/delete', [AdminSubscriberController::class, 'delete']);

// ============================================================
//  Post / Page Catch-All (MUST be registered last)
//  Tries to resolve as a post first, then falls back to a static page.
// ============================================================

$router->get('/{slug}', [PostController::class, 'show']);
