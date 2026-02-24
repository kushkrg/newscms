<?php

namespace App\Models;

use App\Core\Database;

class Redirect
{
    /**
     * Return all redirect rules, ordered by creation date.
     */
    public static function all(): array
    {
        return Database::query(
            "SELECT * FROM redirects ORDER BY created_at DESC"
        )->fetchAll();
    }

    /**
     * Insert a new redirect rule and return the new ID.
     */
    public static function create(array $data): int
    {
        $columns      = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn(string $k) => ':' . $k, array_keys($data)));

        Database::query(
            "INSERT INTO redirects ({$columns}) VALUES ({$placeholders})",
            $data
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Delete a redirect rule by ID.
     */
    public static function delete(int $id): void
    {
        Database::query(
            "DELETE FROM redirects WHERE id = :id",
            ['id' => $id]
        );
    }
}
