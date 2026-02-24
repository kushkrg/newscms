<?php
/**
 * Admin Login Page
 * Layout: layouts/minimal
 */
?>

<div class="login-page">

    <h1 class="login-heading">Admin</h1>
    <p class="login-subheading">Sign in to your account</p>

    <?php $flashSuccess = \App\Core\Session::getFlash('success'); ?>
    <?php $flashError = \App\Core\Session::getFlash('error'); ?>

    <?php if ($flashSuccess): ?>
    <div class="flash-message flash-success">
        <?= h($flashSuccess) ?>
    </div>
    <?php endif; ?>

    <?php if ($flashError): ?>
    <div class="flash-message flash-error">
        <?= h($flashError) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('admin/login') ?>" class="login-form" novalidate>
        <?= \App\Core\Csrf::field() ?>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="form-input"
                required
                autofocus
                autocomplete="email"
                placeholder="you@example.com"
            >
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form-input"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
            >
        </div>

        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>

</div>

<style>
    .login-page {
        text-align: center;
    }

    .login-heading {
        font-size: 1.75rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 4px;
        letter-spacing: -0.025em;
    }

    .login-subheading {
        font-size: 0.875rem;
        color: #666;
        margin-bottom: 28px;
    }

    .login-form {
        text-align: left;
    }

    .login-form .form-group {
        margin-bottom: 20px;
    }

    .login-form .form-label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #111;
        margin-bottom: 6px;
    }

    .login-form .form-input {
        width: 100%;
        padding: 10px 12px;
        font-size: 0.875rem;
        font-family: inherit;
        color: #111;
        background-color: #fff;
        border: 1px solid #d1d1d1;
        border-radius: 6px;
        outline: none;
        transition: border-color 0.15s ease;
    }

    .login-form .form-input:focus {
        border-color: #000;
        box-shadow: 0 0 0 1px #000;
    }

    .login-form .form-input::placeholder {
        color: #aaa;
    }

    .login-form .btn-primary {
        display: block;
        width: 100%;
        padding: 10px 16px;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: inherit;
        color: #fff;
        background-color: #000;
        border: 1px solid #000;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.15s ease;
        margin-top: 24px;
    }

    .login-form .btn-primary:hover {
        background-color: #222;
    }

    .login-form .btn-primary:active {
        background-color: #000;
    }

    .flash-message {
        padding: 10px 14px;
        border-radius: 6px;
        font-size: 0.8125rem;
        margin-bottom: 20px;
        text-align: left;
    }

    .flash-success {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        color: #111;
    }

    .flash-error {
        background-color: #fafafa;
        border: 1px solid #111;
        color: #111;
    }
</style>
