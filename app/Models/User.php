<?php

namespace App\Models;

use App\Core\Database;

class User
{
    /**
     * Find a user by primary key.
     */
    public static function find(int $id): ?array
    {
        $row = Database::query(
            "SELECT * FROM users WHERE id = :id LIMIT 1",
            ['id' => $id]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Find a user by email address.
     */
    public static function findByEmail(string $email): ?array
    {
        $row = Database::query(
            "SELECT * FROM users WHERE email = :email LIMIT 1",
            ['email' => $email]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Find a user by URL-friendly slug.
     */
    public static function findBySlug(string $slug): ?array
    {
        $row = Database::query(
            "SELECT * FROM users WHERE slug = :slug LIMIT 1",
            ['slug' => $slug]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Return a paginated list of users.
     */
    public static function all(int $limit, int $offset): array
    {
        return Database::query(
            "SELECT * FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        )->fetchAll();
    }

    /**
     * Count total users.
     */
    public static function count(): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM users"
        )->fetch()['total'];
    }

    /**
     * Insert a new user and return the new ID.
     */
    public static function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn(string $k) => ':' . $k, array_keys($data)));

        Database::query(
            "INSERT INTO users ({$columns}) VALUES ({$placeholders})",
            $data
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Update an existing user by ID.
     */
    public static function update(int $id, array $data): void
    {
        $setClauses = implode(', ', array_map(fn(string $k) => "{$k} = :{$k}", array_keys($data)));
        $data['id'] = $id;

        Database::query(
            "UPDATE users SET {$setClauses} WHERE id = :id",
            $data
        );
    }

    /**
     * Delete a user by ID.
     */
    public static function delete(int $id): void
    {
        Database::query(
            "DELETE FROM users WHERE id = :id",
            ['id' => $id]
        );
    }
}
