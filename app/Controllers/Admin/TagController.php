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
use App\Models\Tag;

class TagController
{
    /**
     * Run before every action -- require a logged-in user.
     */
    public function middleware(): void
    {
        Auth::requireAuth();
    }

    /* ------------------------------------------------------------------
     *  INDEX  --  GET /admin/tags
     * ----------------------------------------------------------------*/

    public function index(Request $request, array $params): void
    {
        $tags = Tag::all();

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/tags/index', [
            'pageTitle'   => 'Tags',
            'currentPage' => 'tags',
            'tags'      => $tags,
        ]);
    }

    /* ------------------------------------------------------------------
     *  STORE  --  POST /admin/tags
     * ----------------------------------------------------------------*/

    public function store(Request $request, array $params): void
    {
        Csrf::check();

        $data = [
            'name' => trim((string) $request->post('name', '')),
        ];

        // Validation
        $validator = new Validator($data);
        $valid = $validator->validate([
            'name' => ['required', 'max:100'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Response::redirect(url('admin/tags'));
        }

        // Generate slug
        $slug = Sanitizer::slug($data['name']);
        $slug = Sanitizer::uniqueSlug($slug, 'tags');

        // Build insert data
        $tagData = [
            'name'       => $data['name'],
            'slug'       => $slug,
            'post_count' => 0,
        ];

        Tag::create($tagData);

        Session::flash('success', 'Tag created successfully.');
        Response::redirect(url('admin/tags'));
    }

    /* ------------------------------------------------------------------
     *  DELETE  --  POST /admin/tags/{id}/delete
     * ----------------------------------------------------------------*/

    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $tagId = (int) $params['id'];
        $tag   = Tag::find($tagId);
        if (!$tag) {
            Session::flash('error', 'Tag not found.');
            Response::redirect(url('admin/tags'));
        }

        Tag::delete($tagId);

        Session::flash('success', 'Tag deleted successfully.');
        Response::redirect(url('admin/tags'));
    }
}
