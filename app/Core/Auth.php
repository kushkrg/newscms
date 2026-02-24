<?php

namespace App\Core;

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $stmt = Database::query("SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1", [$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        Session::regenerate();
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);

        // Update last login
        Database::query("UPDATE users SET last_login_at = NOW() WHERE id = ?", [$user['id']]);

        return true;
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function id(): ?int
    {
        $id = Session::get('user_id');
        return $id ? (int) $id : null;
    }

    public static function user(): ?array
    {
        if (!self::check()) return null;

        static $user = null;
        if ($user === null) {
            $stmt = Database::query("SELECT * FROM users WHERE id = ? LIMIT 1", [self::id()]);
            $user = $stmt->fetch() ?: null;
        }
        return $user;
    }

    public static function role(): ?string
    {
        return Session::get('user_role');
    }

    public static function hasRole(string|array $roles): bool
    {
        $currentRole = self::role();
        if (!$currentRole) return false;

        if (is_string($roles)) $roles = [$roles];
        return in_array($currentRole, $roles, true);
    }

    public static function isAdmin(): bool
    {
        return self::hasRole(['super_admin', 'editor']);
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            Session::flash('error', 'Please login to continue.');
            Response::redirect(url('admin/login'));
        }
    }

    public static function requireRole(string|array $roles): void
    {
        self::requireAuth();
        if (!self::hasRole($roles)) {
            http_response_code(403);
            exit('Forbidden: Insufficient permissions.');
        }
    }
}
