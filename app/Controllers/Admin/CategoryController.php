<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Sanitizer;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Models\Category;

class CategoryController
{
    /**
     * Run before every action -- require a logged-in user.
     */
    public function middleware(): void
    {
        Auth::requireAuth();
    }

    /* ------------------------------------------------------------------
     *  INDEX  --  GET /admin/categories
     * ----------------------------------------------------------------*/

    public function index(Request $request, array $params): void
    {
        $categories = Category::allWithCount();

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/categories/index', [
            'pageTitle'    => 'Categories',
            'currentPage'  => 'categories',
            'categories'   => $categories,
            'editCategory' => null,
        ]);
    }

    /* ------------------------------------------------------------------
     *  STORE  --  POST /admin/categories
     * ----------------------------------------------------------------*/

    public function store(Request $request, array $params): void
    {
        Csrf::check();

        $data = [
            'name'        => trim((string) $request->post('name', '')),
            'description' => trim((string) $request->post('description', '')),
            'parent_id'   => $request->post('parent_id') ?: null,
            'sort_order'  => (int) $request->post('sort_order', 0),
        ];

        // Validation
        $validator = new Validator($data);
        $valid = $validator->validate([
            'name' => ['required', 'max:200'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Response::redirect(url('admin/categories'));
        }

        // Generate slug
        $slug = Sanitizer::slug($data['name']);
        $slug = Sanitizer::uniqueSlug($slug, 'categories');

        // Build insert data
        $categoryData = [
            'name'        => $data['name'],
            'slug'        => $slug,
            'description' => $data['description'] ?: null,
            'parent_id'   => $data['parent_id'] ? (int) $data['parent_id'] : null,
            'sort_order'  => $data['sort_order'],
            'post_count'  => 0,
        ];

        Category::create($categoryData);

        Session::flash('success', 'Category created successfully.');
        Response::redirect(url('admin/categories'));
    }

    /* ------------------------------------------------------------------
     *  EDIT  --  GET /admin/categories/{id}/edit
     * ----------------------------------------------------------------*/

    public function edit(Request $request, array $params): void
    {
        $category = Category::find((int) $params['id']);
        if (!$category) {
            Session::flash('error', 'Category not found.');
            Response::redirect(url('admin/categories'));
        }

        $categories = Category::allWithCount();

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/categories/index', [
            'pageTitle'    => 'Edit Category',
            'currentPage'  => 'categories',
            'categories'   => $categories,
            'editCategory' => $category,
        ]);
    }

    /* ------------------------------------------------------------------
     *  UPDATE  --  POST /admin/categories/{id}
     * ----------------------------------------------------------------*/

    public function update(Request $request, array $params): void
    {
        Csrf::check();

        $categoryId = (int) $params['id'];
        $category   = Category::find($categoryId);
        if (!$category) {
            Session::flash('error', 'Category not found.');
            Response::redirect(url('admin/categories'));
        }

        $data = [
            'name'        => trim((string) $request->post('name', '')),
            'description' => trim((string) $request->post('description', '')),
            'parent_id'   => $request->post('parent_id') ?: null,
            'sort_order'  => (int) $request->post('sort_order', 0),
        ];

        // Validation
        $validator = new Validator($data);
        $valid = $validator->validate([
            'name' => ['required', 'max:200'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Response::redirect(url("admin/categories/{$categoryId}/edit"));
        }

        // Handle slug -- regenerate from name if name changed
        $newSlug = trim((string) $request->post('slug', ''));
        if ($newSlug !== '' && $newSlug !== $category['slug']) {
            $newSlug = Sanitizer::slug($newSlug);
            $newSlug = Sanitizer::uniqueSlug($newSlug, 'categories', $categoryId);
        } else {
            $newSlug = $category['slug'];
        }

        // Prevent assigning self as parent
        $parentId = $data['parent_id'] ? (int) $data['parent_id'] : null;
        if ($parentId === $categoryId) {
            $parentId = null;
        }

        // Build update data
        $updateData = [
            'name'        => $data['name'],
            'slug'        => $newSlug,
            'description' => $data['description'] ?: null,
            'parent_id'   => $parentId,
            'sort_order'  => $data['sort_order'],
        ];

        Category::update($categoryId, $updateData);

        Session::flash('success', 'Category updated successfully.');
        Response::redirect(url('admin/categories'));
    }

    /* ------------------------------------------------------------------
     *  DELETE  --  POST /admin/categories/{id}/delete
     * ----------------------------------------------------------------*/

    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $categoryId = (int) $params['id'];
        $category   = Category::find($categoryId);
        if (!$category) {
            Session::flash('error', 'Category not found.');
            Response::redirect(url('admin/categories'));
        }

        // Don't delete if the category has posts
        $postCount = (int) Database::query(
            "SELECT COUNT(*) AS total FROM posts WHERE category_id = :id",
            ['id' => $categoryId]
        )->fetch()['total'];

        if ($postCount > 0) {
            Session::flash('error', "Cannot delete category \"{$category['name']}\" because it has {$postCount} post(s). Reassign or delete those posts first.");
            Response::redirect(url('admin/categories'));
        }

        Category::delete($categoryId);

        Session::flash('success', 'Category deleted successfully.');
        Response::redirect(url('admin/categories'));
    }
}
