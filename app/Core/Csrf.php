<?php

namespace App\Core;

class Csrf
{
    public static function generate(): string
    {
        $token = bin2hex(random_bytes(32));
        Session::set('_csrf_token', $token);
        return $token;
    }

    public static function token(): string
    {
        $token = Session::get('_csrf_token');
        if (!$token) {
            $token = self::generate();
        }
        return $token;
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . h(self::token()) . '">';
    }

    public static function validate(?string $token = null): bool
    {
        $token = $token ?? ($_POST['_csrf_token'] ?? '');
        $sessionToken = Session::get('_csrf_token', '');

        if (empty($token) || empty($sessionToken)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function check(): void
    {
        if (!self::validate()) {
            http_response_code(403);
            exit('CSRF token mismatch.');
        }
    }
}
