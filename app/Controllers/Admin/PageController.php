<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Sanitizer;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Models\Page;

class PageController
{
    /**
     * Run before every action -- require a logged-in user.
     */
    public function middleware(): void
    {
        Auth::requireAuth();
    }

    /* ------------------------------------------------------------------
     *  INDEX  --  GET /admin/pages
     * ----------------------------------------------------------------*/

    public function index(Request $request, array $params): void
    {
        $pages = Page::all();

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/pages/index', [
            'pageTitle'   => 'Pages',
            'currentPage' => 'pages',
            'pages'     => $pages,
        ]);
    }

    /* ------------------------------------------------------------------
     *  CREATE  --  GET /admin/pages/create
     * ----------------------------------------------------------------*/

    public function create(Request $request, array $params): void
    {
        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/pages/create', [
            'pageTitle'   => 'Create Page',
            'currentPage' => 'pages',
        ]);
    }

    /* ------------------------------------------------------------------
     *  STORE  --  POST /admin/pages
     * ----------------------------------------------------------------*/

    public function store(Request $request, array $params): void
    {
        Csrf::check();

        $data = [
            'title'            => trim((string) $request->post('title', '')),
            'content'          => (string) $request->post('content', ''),
            'status'           => $request->post('status', 'draft'),
            'meta_title'       => trim((string) $request->post('meta_title', '')),
            'meta_description' => trim((string) $request->post('meta_description', '')),
            'sort_order'       => (int) $request->post('sort_order', 0),
        ];

        // Validation
        $validator = new Validator($data);
        $valid = $validator->validate([
            'title'   => ['required', 'max:300'],
            'content' => ['required'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Session::flash('old', $data);
            Response::redirect(url('admin/pages/create'));
        }

        // Generate slug
        $slug = Sanitizer::slug($data['title']);
        $slug = Sanitizer::uniqueSlug($slug, 'pages');

        // Clean HTML content
        $data['content'] = Sanitizer::cleanHtml($data['content']);

        // Build insert data
        $pageData = [
            'title'            => $data['title'],
            'slug'             => $slug,
            'content'          => $data['content'],
            'template'         => $request->post('template', 'default'),
            'status'           => $data['status'],
            'user_id'          => Auth::id(),
            'meta_title'       => $data['meta_title'] ?: null,
            'meta_description' => $data['meta_description'] ?: null,
            'sort_order'       => $data['sort_order'],
        ];

        Page::create($pageData);

        Session::flash('success', 'Page created successfully.');
        Response::redirect(url('admin/pages'));
    }

    /* ------------------------------------------------------------------
     *  EDIT  --  GET /admin/pages/{id}/edit
     * ----------------------------------------------------------------*/

    public function edit(Request $request, array $params): void
    {
        $page = Page::find((int) $params['id']);
        if (!$page) {
            Session::flash('error', 'Page not found.');
            Response::redirect(url('admin/pages'));
        }

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/pages/edit', [
            'pageTitle'   => 'Edit Page',
            'currentPage' => 'pages',
            'page'      => $page,
        ]);
    }

    /* ------------------------------------------------------------------
     *  UPDATE  --  POST /admin/pages/{id}
     * ----------------------------------------------------------------*/

    public function update(Request $request, array $params): void
    {
        Csrf::check();

        $pageId = (int) $params['id'];
        $page   = Page::find($pageId);
        if (!$page) {
            Session::flash('error', 'Page not found.');
            Response::redirect(url('admin/pages'));
        }

        $data = [
            'title'            => trim((string) $request->post('title', '')),
            'content'          => (string) $request->post('content', ''),
            'status'           => $request->post('status', 'draft'),
            'meta_title'       => trim((string) $request->post('meta_title', '')),
            'meta_description' => trim((string) $request->post('meta_description', '')),
            'sort_order'       => (int) $request->post('sort_order', 0),
        ];

        // Validation
        $validator = new Validator($data);
        $valid = $validator->validate([
            'title'   => ['required', 'max:300'],
            'content' => ['required'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Response::redirect(url("admin/pages/{$pageId}/edit"));
        }

        // Handle slug -- keep existing unless explicitly changed
        $newSlug = trim((string) $request->post('slug', ''));
        if ($newSlug !== '' && $newSlug !== $page['slug']) {
            $newSlug = Sanitizer::slug($newSlug);
            $newSlug = Sanitizer::uniqueSlug($newSlug, 'pages', $pageId);
        } else {
            $newSlug = $page['slug'];
        }

        // Clean HTML content
        $data['content'] = Sanitizer::cleanHtml($data['content']);

        // Build update data
        $updateData = [
            'title'            => $data['title'],
            'slug'             => $newSlug,
            'content'          => $data['content'],
            'template'         => $request->post('template', 'default'),
            'status'           => $data['status'],
            'meta_title'       => $data['meta_title'] ?: null,
            'meta_description' => $data['meta_description'] ?: null,
            'sort_order'       => $data['sort_order'],
        ];

        Page::update($pageId, $updateData);

        Session::flash('success', 'Page updated successfully.');
        Response::redirect(url('admin/pages'));
    }

    /* ------------------------------------------------------------------
     *  DELETE  --  POST /admin/pages/{id}/delete
     * ----------------------------------------------------------------*/

    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $pageId = (int) $params['id'];
        $page   = Page::find($pageId);
        if (!$page) {
            Session::flash('error', 'Page not found.');
            Response::redirect(url('admin/pages'));
        }

        Page::delete($pageId);

        Session::flash('success', 'Page deleted successfully.');
        Response::redirect(url('admin/pages'));
    }
}
