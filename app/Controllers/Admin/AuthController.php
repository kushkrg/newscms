<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;

class AuthController
{
    /**
     * Display the admin login form.
     *
     * If the user is already authenticated, redirect straight to the dashboard.
     */
    public function loginForm(Request $request, array $params): void
    {
        if (Auth::check()) {
            Response::redirect(url('admin'));
        }

        $view = new View();
        $view->setLayout('layouts/minimal');
        echo $view->render('admin/auth/login', [
            'pageTitle' => 'Admin Login',
        ]);
    }

    /**
     * Handle the POST login attempt.
     *
     * Validates CSRF, enforces rate-limiting (max 5 failures per IP stored in
     * session), then delegates credential verification to Auth::attempt().
     */
    public function login(Request $request, array $params): void
    {
        Csrf::check();

        $ip = $request->ip();
        $rateLimitKey = 'failed_logins_' . $ip;
        $failedAttempts = (int) Session::get($rateLimitKey, 0);

        // ---- Rate-limit: block after 5 consecutive failures ----
        if ($failedAttempts >= 5) {
            Session::flash('error', 'Too many failed login attempts. Please try again later.');
            Response::redirect(url('admin/login'));
        }

        $email    = trim((string) $request->post('email', ''));
        $password = (string) $request->post('password', '');

        // ---- Basic input presence check ----
        if ($email === '' || $password === '') {
            Session::flash('error', 'Email and password are required.');
            Response::redirect(url('admin/login'));
        }

        // ---- Attempt authentication ----
        if (Auth::attempt($email, $password)) {
            // Reset failure counter on success
            Session::remove($rateLimitKey);
            Response::redirect(url('admin'));
        }

        // ---- Failed attempt ----
        Session::set($rateLimitKey, $failedAttempts + 1);
        Session::flash('error', 'Invalid email or password.');
        Response::redirect(url('admin/login'));
    }

    /**
     * Handle the POST logout action.
     *
     * Validates CSRF, destroys the session, and redirects to the login page.
     */
    public function logout(Request $request, array $params): void
    {
        Csrf::check();
        Auth::logout();
        Response::redirect(url('admin/login'));
    }
}
