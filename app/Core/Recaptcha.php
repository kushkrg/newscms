<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Setting;

/**
 * Google reCAPTCHA v3 helper.
 *
 * Usage in views:
 *   <!-- Load script once in layout -->
 *   <?= \App\Core\Recaptcha::script() ?>
 *
 *   <!-- In each form -->
 *   <?= \App\Core\Recaptcha::field('comment') ?>
 *
 * Usage in controllers:
 *   if (!\App\Core\Recaptcha::verify($request->post('g-recaptcha-response'), 'comment')) { ... }
 */
class Recaptcha
{
    private static ?bool $enabled = null;

    /**
     * Whether reCAPTCHA is fully configured and enabled.
     */
    public static function isEnabled(): bool
    {
        if (self::$enabled === null) {
            self::$enabled = (bool) Setting::get('recaptcha_enabled', 0)
                && Setting::get('recaptcha_site_key', '') !== ''
                && Setting::get('recaptcha_secret_key', '') !== '';
        }
        return self::$enabled;
    }

    /**
     * Return the public site key.
     */
    public static function getSiteKey(): string
    {
        return (string) Setting::get('recaptcha_site_key', '');
    }

    /**
     * Return the minimum score threshold (0.0 – 1.0).
     */
    public static function getMinScore(): float
    {
        return (float) Setting::get('recaptcha_min_score', 0.5);
    }

    /**
     * Output the reCAPTCHA v3 JS script tag.
     * Call once per page, e.g. in the <head> or before </body>.
     */
    public static function script(): string
    {
        if (!self::isEnabled()) {
            return '';
        }

        $siteKey = htmlspecialchars(self::getSiteKey(), ENT_QUOTES, 'UTF-8');

        return '<script src="https://www.google.com/recaptcha/api.js?render=' . $siteKey . '"></script>' . "\n"
             . '<script>var RECAPTCHA_SITE_KEY="' . $siteKey . '";</script>' . "\n";
    }

    /**
     * Output a hidden input for the given action.
     * The global JS (main.js) will populate the token on form submit.
     */
    public static function field(string $action = 'submit'): string
    {
        if (!self::isEnabled()) {
            return '';
        }

        $action = htmlspecialchars($action, ENT_QUOTES, 'UTF-8');

        return '<input type="hidden" name="g-recaptcha-response" '
             . 'class="g-recaptcha-response" '
             . 'data-action="' . $action . '">';
    }

    /**
     * Verify a reCAPTCHA token server-side.
     *
     * @param string $token           The token from the form (g-recaptcha-response)
     * @param string $expectedAction  The expected action name (e.g. 'comment')
     * @return bool
     */
    public static function verify(string $token, string $expectedAction = ''): bool
    {
        // If reCAPTCHA is not enabled, always pass
        if (!self::isEnabled()) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        $secret  = (string) Setting::get('recaptcha_secret_key', '');
        $payload = [
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ];

        // Use cURL if available, otherwise file_get_contents
        $responseBody = self::postRequest(
            'https://www.google.com/recaptcha/api/siteverify',
            $payload
        );

        if (!$responseBody) {
            return false;
        }

        $data = json_decode($responseBody, true);

        if (!is_array($data) || empty($data['success'])) {
            return false;
        }

        // Verify action matches
        if ($expectedAction !== '' && isset($data['action']) && $data['action'] !== $expectedAction) {
            return false;
        }

        // Verify score meets minimum threshold
        $minScore = self::getMinScore();
        if (isset($data['score']) && (float) $data['score'] < $minScore) {
            return false;
        }

        return true;
    }

    /**
     * Make a POST request and return the response body.
     */
    private static function postRequest(string $url, array $data): string|false
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }

        // Fallback to file_get_contents with stream context
        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
                'timeout' => 10,
            ],
        ]);

        return @file_get_contents($url, false, $context);
    }
}
