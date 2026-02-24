<?php

namespace App\Models;

use App\Core\Database;

class Comment
{
    /**
     * Find a single comment by primary key.
     */
    public static function find(int $id): ?array
    {
        $row = Database::query(
            "SELECT * FROM comments WHERE id = :id LIMIT 1",
            ['id' => $id]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Get approved comments for a post, structured as a threaded tree.
     *
     * Returns a flat list of top-level comments, each carrying a 'replies' array.
     */
    public static function byPost(int $postId): array
    {
        $rows = Database::query(
            "SELECT * FROM comments
             WHERE post_id = :post_id AND status = 'approved'
             ORDER BY created_at ASC",
            ['post_id' => $postId]
        )->fetchAll();

        return self::buildTree($rows);
    }

    /**
     * Build a threaded tree from a flat list of comment rows.
     */
    private static function buildTree(array $rows): array
    {
        $indexed = [];
        foreach ($rows as &$row) {
            $row['replies'] = [];
            $indexed[$row['id']] = &$row;
        }
        unset($row);

        $tree = [];
        foreach ($indexed as &$comment) {
            if (!empty($comment['parent_id']) && isset($indexed[$comment['parent_id']])) {
                $indexed[$comment['parent_id']]['replies'][] = &$comment;
            } else {
                $tree[] = &$comment;
            }
        }
        unset($comment);

        return $tree;
    }

    /**
     * Paginated admin list of comments, optionally filtered by status.
     * Includes the post title via JOIN.
     */
    public static function adminList(string $status, int $limit, int $offset): array
    {
        $where  = '';
        $params = ['limit' => $limit, 'offset' => $offset];

        if ($status !== '') {
            $where            = 'WHERE c.status = :status';
            $params['status'] = $status;
        }

        return Database::query(
            "SELECT c.*, p.title AS post_title, p.slug AS post_slug
             FROM comments c
             LEFT JOIN posts p ON p.id = c.post_id
             {$where}
             ORDER BY c.created_at DESC
             LIMIT :limit OFFSET :offset",
            $params
        )->fetchAll();
    }

    /**
     * Count comments, optionally filtered by status.
     */
    public static function adminCount(string $status = ''): int
    {
        if ($status !== '') {
            return (int) Database::query(
                "SELECT COUNT(*) AS total FROM comments WHERE status = :status",
                ['status' => $status]
            )->fetch()['total'];
        }

        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM comments"
        )->fetch()['total'];
    }

    /**
     * Insert a new comment and return the new ID.
     */
    public static function create(array $data): int
    {
        $columns      = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn(string $k) => ':' . $k, array_keys($data)));

        Database::query(
            "INSERT INTO comments ({$columns}) VALUES ({$placeholders})",
            $data
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Update a comment's moderation status (approved, pending, spam, etc.).
     */
    public static function updateStatus(int $id, string $status): void
    {
        Database::query(
            "UPDATE comments SET status = :status WHERE id = :id",
            ['status' => $status, 'id' => $id]
        );
    }

    /**
     * Delete a comment by ID.
     */
    public static function delete(int $id): void
    {
        Database::query(
            "DELETE FROM comments WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Count comments that are pending moderation.
     */
    public static function pendingCount(): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM comments WHERE status = 'pending'"
        )->fetch()['total'];
    }
}
