<?php

namespace App\Models;

use App\Core\Database;

class Page
{
    /**
     * Find a page by primary key.
     */
    public static function find(int $id): ?array
    {
        $row = Database::query(
            "SELECT * FROM pages WHERE id = :id LIMIT 1",
            ['id' => $id]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Find a page by its slug.
     */
    public static function findBySlug(string $slug): ?array
    {
        $row = Database::query(
            "SELECT * FROM pages WHERE slug = :slug LIMIT 1",
            ['slug' => $slug]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Return all published pages ordered by sort_order / title.
     */
    public static function published(): array
    {
        return Database::query(
            "SELECT * FROM pages
             WHERE status = 'published'
             ORDER BY sort_order ASC, title ASC"
        )->fetchAll();
    }

    /**
     * Return all pages regardless of status (admin use).
     */
    public static function all(): array
    {
        return Database::query(
            "SELECT * FROM pages ORDER BY sort_order ASC, title ASC"
        )->fetchAll();
    }

    /**
     * Insert a new page and return the new ID.
     */
    public static function create(array $data): int
    {
        $columns      = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn(string $k) => ':' . $k, array_keys($data)));

        Database::query(
            "INSERT INTO pages ({$columns}) VALUES ({$placeholders})",
            $data
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Update an existing page by ID.
     */
    public static function update(int $id, array $data): void
    {
        $setClauses = implode(', ', array_map(fn(string $k) => "{$k} = :{$k}", array_keys($data)));
        $data['id'] = $id;

        Database::query(
            "UPDATE pages SET {$setClauses} WHERE id = :id",
            $data
        );
    }

    /**
     * Delete a page by ID.
     */
    public static function delete(int $id): void
    {
        Database::query(
            "DELETE FROM pages WHERE id = :id",
            ['id' => $id]
        );
    }
}
