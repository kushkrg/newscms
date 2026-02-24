<?php

namespace App\Models;

use App\Core\Database;

class Category
{
    /**
     * Find a category by primary key.
     */
    public static function find(int $id): ?array
    {
        $row = Database::query(
            "SELECT * FROM categories WHERE id = :id LIMIT 1",
            ['id' => $id]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Find a category by its slug.
     */
    public static function findBySlug(string $slug): ?array
    {
        $row = Database::query(
            "SELECT * FROM categories WHERE slug = :slug LIMIT 1",
            ['slug' => $slug]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Return all categories ordered by sort_order.
     */
    public static function all(): array
    {
        return Database::query(
            "SELECT * FROM categories ORDER BY sort_order ASC, name ASC"
        )->fetchAll();
    }

    /**
     * Return all categories with actual post counts derived from a JOIN.
     */
    public static function allWithCount(): array
    {
        return Database::query(
            "SELECT c.*, COUNT(p.id) AS post_count
             FROM categories c
             LEFT JOIN posts p ON p.category_id = c.id AND p.status = 'published'
             GROUP BY c.id
             ORDER BY c.sort_order ASC, c.name ASC"
        )->fetchAll();
    }

    /**
     * Insert a new category and return the new ID.
     */
    public static function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn(string $k) => ':' . $k, array_keys($data)));

        Database::query(
            "INSERT INTO categories ({$columns}) VALUES ({$placeholders})",
            $data
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Update an existing category by ID.
     */
    public static function update(int $id, array $data): void
    {
        $setClauses = implode(', ', array_map(fn(string $k) => "{$k} = :{$k}", array_keys($data)));
        $data['id'] = $id;

        Database::query(
            "UPDATE categories SET {$setClauses} WHERE id = :id",
            $data
        );
    }

    /**
     * Delete a category by ID.
     */
    public static function delete(int $id): void
    {
        Database::query(
            "DELETE FROM categories WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Recount published posts for a given category and update its post_count column.
     */
    public static function updatePostCount(int $id): void
    {
        Database::query(
            "UPDATE categories
             SET post_count = (
                 SELECT COUNT(*) FROM posts
                 WHERE posts.category_id = :cat_id AND posts.status = 'published'
             )
             WHERE id = :id",
            ['cat_id' => $id, 'id' => $id]
        );
    }
}
