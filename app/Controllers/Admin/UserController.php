<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Core\Sanitizer;
use App\Core\Session;
use App\Core\Uploader;
use App\Core\Validator;
use App\Core\View;
use App\Models\User;

class UserController
{
    /**
     * Gate: only super admins may manage users.
     */
    public function middleware(): void
    {
        Auth::requireRole(['super_admin']);
    }

    // ------------------------------------------------------------------
    //  LIST
    // ------------------------------------------------------------------

    /**
     * Display a paginated list of all users.
     */
    public function index(Request $request, array $params): void
    {
        $page      = max(1, (int) $request->get('page', 1));
        $perPage   = 15;
        $total     = User::count();
        $paginator = new Paginator($total, $perPage, $page);
        $users     = User::all($paginator->perPage, $paginator->offset);

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/users/index', [
            'pageTitle'   => 'Manage Users',
            'currentPage' => 'users',
            'users'     => $users,
            'paginator' => $paginator,
        ]);
    }

    // ------------------------------------------------------------------
    //  CREATE
    // ------------------------------------------------------------------

    /**
     * Show the blank user creation form.
     */
    public function create(Request $request, array $params): void
    {
        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/users/form', [
            'pageTitle'   => 'Create User',
            'currentPage' => 'users',
            'user'      => null,
        ]);
    }

    /**
     * Validate and store a new user.
     */
    public function store(Request $request, array $params): void
    {
        Csrf::check();

        $data = [
            'name'     => trim((string) $request->post('name', '')),
            'email'    => trim((string) $request->post('email', '')),
            'password' => (string) $request->post('password', ''),
            'role'     => trim((string) $request->post('role', 'editor')),
        ];

        $validator = new Validator($data);
        $valid = $validator->validate([
            'name'     => ['required', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'role'     => ['required', 'in:super_admin,editor,author,contributor'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Session::flash('old', $data);
            Response::redirect(url('admin/users/create'));
        }

        $slug = Sanitizer::slug($data['name']);
        $slug = Sanitizer::uniqueSlug($slug, 'users');

        User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            'role'          => $data['role'],
            'slug'          => $slug,
            'is_active'     => 1,
        ]);

        Session::flash('success', 'User created successfully.');
        Response::redirect(url('admin/users'));
    }

    // ------------------------------------------------------------------
    //  EDIT
    // ------------------------------------------------------------------

    /**
     * Show the populated edit form for an existing user.
     */
    public function edit(Request $request, array $params): void
    {
        $user = User::find((int) ($params['id'] ?? 0));

        if (!$user) {
            Session::flash('error', 'User not found.');
            Response::redirect(url('admin/users'));
        }

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/users/form', [
            'pageTitle'   => 'Edit User',
            'currentPage' => 'users',
            'user'      => $user,
        ]);
    }

    /**
     * Validate and update an existing user.
     *
     * Password is only updated when a new value is provided. Avatar upload
     * is handled via Uploader::uploadAvatar() when a file is present.
     */
    public function update(Request $request, array $params): void
    {
        Csrf::check();

        $id   = (int) ($params['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            Session::flash('error', 'User not found.');
            Response::redirect(url('admin/users'));
        }

        $data = [
            'name'     => trim((string) $request->post('name', '')),
            'email'    => trim((string) $request->post('email', '')),
            'password' => (string) $request->post('password', ''),
            'role'     => trim((string) $request->post('role', 'editor')),
        ];

        // Build validation rules; email uniqueness must ignore current user.
        $rules = [
            'name'  => ['required', 'max:100'],
            'email' => ['required', 'email', "unique:users,email,{$id}"],
            'role'  => ['required', 'in:super_admin,editor,author'],
        ];

        // Password is optional on update; only validate when provided.
        if ($data['password'] !== '') {
            $rules['password'] = ['min:6'];
        }

        $validator = new Validator($data);
        $valid = $validator->validate($rules);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Session::flash('old', $data);
            Response::redirect(url("admin/users/{$id}/edit"));
        }

        // ---- Build the column set to persist ----
        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'role'  => $data['role'],
            'slug'  => Sanitizer::uniqueSlug(Sanitizer::slug($data['name']), 'users', $id),
        ];

        if ($data['password'] !== '') {
            $updateData['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        // ---- Avatar upload ----
        $avatarFile = $request->file('avatar');
        if ($avatarFile && $avatarFile['error'] === UPLOAD_ERR_OK) {
            $uploader   = new Uploader();
            $avatarPath = $uploader->uploadAvatar($avatarFile);

            if ($avatarPath) {
                // Remove old avatar file if one exists
                if (!empty($user['avatar'])) {
                    Uploader::deleteFile($user['avatar']);
                }
                $updateData['avatar'] = $avatarPath;
            } else {
                $errors = $uploader->errors();
                Session::flash('error', $errors[0] ?? 'Avatar upload failed.');
                Response::redirect(url("admin/users/{$id}/edit"));
            }
        }

        User::update($id, $updateData);

        Session::flash('success', 'User updated successfully.');
        Response::redirect(url('admin/users'));
    }

    // ------------------------------------------------------------------
    //  DELETE
    // ------------------------------------------------------------------

    /**
     * Delete a user by ID.
     *
     * Prevents the currently authenticated user from deleting their own account.
     */
    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $id = (int) ($params['id'] ?? 0);

        // Guard: cannot delete yourself
        if ($id === Auth::id()) {
            Session::flash('error', 'You cannot delete your own account.');
            Response::redirect(url('admin/users'));
        }

        $user = User::find($id);

        if (!$user) {
            Session::flash('error', 'User not found.');
            Response::redirect(url('admin/users'));
        }

        // Clean up avatar file if present
        if (!empty($user['avatar'])) {
            Uploader::deleteFile($user['avatar']);
        }

        User::delete($id);

        Session::flash('success', 'User deleted successfully.');
        Response::redirect(url('admin/users'));
    }
}
