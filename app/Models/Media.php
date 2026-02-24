<?php

namespace App\Models;

use App\Core\Database;

class Media
{
    /**
     * Find a media record by primary key.
     */
    public static function find(int $id): ?array
    {
        $row = Database::query(
            "SELECT * FROM media WHERE id = :id LIMIT 1",
            ['id' => $id]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Paginated list of all media, newest first.
     */
    public static function all(int $limit, int $offset): array
    {
        return Database::query(
            "SELECT * FROM media ORDER BY created_at DESC LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        )->fetchAll();
    }

    /**
     * Count total media records.
     */
    public static function count(): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM media"
        )->fetch()['total'];
    }

    /**
     * Insert a new media record and return the new ID.
     */
    public static function create(array $data): int
    {
        $columns      = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn(string $k) => ':' . $k, array_keys($data)));

        Database::query(
            "INSERT INTO media ({$columns}) VALUES ({$placeholders})",
            $data
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Delete a media record by ID.
     */
    public static function delete(int $id): void
    {
        Database::query(
            "DELETE FROM media WHERE id = :id",
            ['id' => $id]
        );
    }
}
