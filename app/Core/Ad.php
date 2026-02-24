<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Setting;

/**
 * Ad helper – renders ad code for a given slot if ads are enabled.
 */
class Ad
{
    /** @var array|null Cached ad settings */
    private static ?array $cache = null;

    /**
     * Load all ad-related settings once and cache them.
     */
    private static function load(): array
    {
        if (self::$cache === null) {
            self::$cache = Setting::getByGroup('ads');
        }

        return self::$cache;
    }

    /**
     * Check if ads are globally enabled.
     */
    public static function enabled(): bool
    {
        $settings = self::load();
        return !empty($settings['ads_enabled']) && $settings['ads_enabled'] === '1';
    }

    /**
     * Get the raw ad code for a specific slot.
     */
    public static function getCode(string $slot): string
    {
        $settings = self::load();
        return trim($settings['ad_' . $slot] ?? '');
    }

    /**
     * Render an ad container for the given slot.
     * Returns empty string if ads are disabled or the slot is empty.
     *
     * @param string $slot  One of: header_banner, sidebar, in_article, after_content, between_posts
     * @param string $class Optional extra CSS class
     */
    public static function render(string $slot, string $class = ''): string
    {
        if (!self::enabled()) {
            return '';
        }

        $code = self::getCode($slot);
        if ($code === '') {
            return '';
        }

        $extraClass = $class ? ' ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') : '';

        return '<div class="ad-container' . $extraClass . '" data-ad-slot="' . htmlspecialchars($slot, ENT_QUOTES, 'UTF-8') . '">'
             . '<span class="ad-container__label">Advertisement</span>'
             . '<div class="ad-container__content">' . $code . '</div>'
             . '</div>';
    }
}
