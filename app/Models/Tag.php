<?php

namespace App\Models;

use App\Core\Database;

class Tag
{
    /**
     * Find a tag by primary key.
     */
    public static function find(int $id): ?array
    {
        $row = Database::query(
            "SELECT * FROM tags WHERE id = :id LIMIT 1",
            ['id' => $id]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Find a tag by its slug.
     */
    public static function findBySlug(string $slug): ?array
    {
        $row = Database::query(
            "SELECT * FROM tags WHERE slug = :slug LIMIT 1",
            ['slug' => $slug]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Return all tags ordered by name.
     */
    public static function all(): array
    {
        return Database::query(
            "SELECT * FROM tags ORDER BY name ASC"
        )->fetchAll();
    }

    /**
     * Find a tag by name or create it. Returns the tag ID in either case.
     */
    public static function findOrCreate(string $name): int
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        $existing = Database::query(
            "SELECT id FROM tags WHERE slug = :slug LIMIT 1",
            ['slug' => $slug]
        )->fetch();

        if ($existing) {
            return (int) $existing['id'];
        }

        Database::query(
            "INSERT INTO tags (name, slug, post_count) VALUES (:name, :slug, 0)",
            ['name' => $name, 'slug' => $slug]
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Insert a new tag and return the new ID.
     */
    public static function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn(string $k) => ':' . $k, array_keys($data)));

        Database::query(
            "INSERT INTO tags ({$columns}) VALUES ({$placeholders})",
            $data
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Delete a tag by ID.
     */
    public static function delete(int $id): void
    {
        Database::query(
            "DELETE FROM post_tags WHERE tag_id = :tag_id",
            ['tag_id' => $id]
        );

        Database::query(
            "DELETE FROM tags WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Return all tags that have zero posts.
     */
    public static function unused(): array
    {
        return Database::query(
            "SELECT * FROM tags WHERE post_count = 0 ORDER BY name ASC"
        )->fetchAll();
    }
}
