<?php

namespace App\Core;

class Sanitizer
{
    public static function slug(string $text): string
    {
        $text = mb_strtolower(trim($text));
        // Transliterate
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('/[^a-z0-9\s\-]/', '', $text);
        $text = preg_replace('/[\s\-]+/', '-', $text);
        return trim($text, '-');
    }

    public static function uniqueSlug(string $slug, string $table, ?int $ignoreId = null): string
    {
        $original = $slug;
        $counter = 2;

        while (true) {
            $sql = "SELECT id FROM $table WHERE slug = ?";
            $params = [$slug];
            if ($ignoreId) {
                $sql .= " AND id != ?";
                $params[] = $ignoreId;
            }
            $existing = Database::query($sql, $params)->fetch();
            if (!$existing) break;
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

    public static function excerpt(string $content, int $length = 160): string
    {
        $text = strip_tags($content);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', trim($text));

        if (mb_strlen($text) <= $length) return $text;

        return mb_substr($text, 0, $length) . '...';
    }

    public static function readingTime(string $content): int
    {
        $text = strip_tags($content);
        $wordCount = str_word_count($text);
        $minutes = (int) ceil($wordCount / 238);
        return max(1, $minutes);
    }

    public static function clean(string $text): string
    {
        return strip_tags(trim($text));
    }

    public static function cleanHtml(string $html): string
    {
        // Basic HTML sanitization - allow safe tags
        $allowed = '<p><br><strong><b><em><i><u><a><ul><ol><li><h2><h3><h4><h5><h6><blockquote><pre><code><img><figure><figcaption><table><thead><tbody><tr><th><td><hr>';
        $html = strip_tags($html, $allowed);

        // Remove any script/event attributes
        $html = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/\s*javascript\s*:/i', '', $html);

        return $html;
    }
}
