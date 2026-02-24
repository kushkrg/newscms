<?php

namespace App\Models;

use App\Core\Database;

class Post
{
    /* ------------------------------------------------------------------
     *  Base SELECT fragments reused across queries
     * ----------------------------------------------------------------*/

    private const BASE_SELECT = "
        SELECT p.*,
               u.name  AS author_name,
               u.slug  AS author_slug,
               u.avatar AS author_avatar,
               c.name  AS category_name,
               c.slug  AS category_slug
        FROM posts p
        LEFT JOIN users u      ON u.id = p.user_id
        LEFT JOIN categories c ON c.id = p.category_id
    ";

    /* ------------------------------------------------------------------
     *  Single-record finders
     * ----------------------------------------------------------------*/

    /**
     * Find a post by primary key, including category and user data.
     */
    public static function find(int $id): ?array
    {
        $row = Database::query(
            self::BASE_SELECT . " WHERE p.id = :id LIMIT 1",
            ['id' => $id]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Find a post by slug, including category and user data.
     */
    public static function findBySlug(string $slug): ?array
    {
        $row = Database::query(
            self::BASE_SELECT . " WHERE p.slug = :slug LIMIT 1",
            ['slug' => $slug]
        )->fetch();

        return $row ?: null;
    }

    /* ------------------------------------------------------------------
     *  Published listing helpers (frontend)
     * ----------------------------------------------------------------*/

    /**
     * Paginated list of published posts ordered by published_at DESC.
     */
    public static function published(int $limit, int $offset): array
    {
        return Database::query(
            self::BASE_SELECT . "
            WHERE p.status = 'published' AND p.published_at <= NOW()
            ORDER BY p.published_at DESC
            LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        )->fetchAll();
    }

    /**
     * Count published posts.
     */
    public static function countPublished(): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM posts
             WHERE status = 'published' AND published_at <= NOW()"
        )->fetch()['total'];
    }

    /* ------------------------------------------------------------------
     *  Filtered published lists
     * ----------------------------------------------------------------*/

    /**
     * Published posts in a specific category.
     */
    public static function byCategory(int $categoryId, int $limit, int $offset): array
    {
        return Database::query(
            self::BASE_SELECT . "
            WHERE p.category_id = :category_id
              AND p.status = 'published' AND p.published_at <= NOW()
            ORDER BY p.published_at DESC
            LIMIT :limit OFFSET :offset",
            ['category_id' => $categoryId, 'limit' => $limit, 'offset' => $offset]
        )->fetchAll();
    }

    public static function countByCategory(int $categoryId): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM posts
             WHERE category_id = :category_id
               AND status = 'published' AND published_at <= NOW()",
            ['category_id' => $categoryId]
        )->fetch()['total'];
    }

    /**
     * Published posts that carry a specific tag (via post_tags pivot).
     */
    public static function byTag(int $tagId, int $limit, int $offset): array
    {
        return Database::query(
            self::BASE_SELECT . "
            INNER JOIN post_tags pt ON pt.post_id = p.id
            WHERE pt.tag_id = :tag_id
              AND p.status = 'published' AND p.published_at <= NOW()
            ORDER BY p.published_at DESC
            LIMIT :limit OFFSET :offset",
            ['tag_id' => $tagId, 'limit' => $limit, 'offset' => $offset]
        )->fetchAll();
    }

    public static function countByTag(int $tagId): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM posts p
             INNER JOIN post_tags pt ON pt.post_id = p.id
             WHERE pt.tag_id = :tag_id
               AND p.status = 'published' AND p.published_at <= NOW()",
            ['tag_id' => $tagId]
        )->fetch()['total'];
    }

    /**
     * Published posts by a specific author.
     */
    public static function byAuthor(int $userId, int $limit, int $offset): array
    {
        return Database::query(
            self::BASE_SELECT . "
            WHERE p.user_id = :user_id
              AND p.status = 'published' AND p.published_at <= NOW()
            ORDER BY p.published_at DESC
            LIMIT :limit OFFSET :offset",
            ['user_id' => $userId, 'limit' => $limit, 'offset' => $offset]
        )->fetchAll();
    }

    public static function countByAuthor(int $userId): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM posts
             WHERE user_id = :user_id
               AND status = 'published' AND published_at <= NOW()",
            ['user_id' => $userId]
        )->fetch()['total'];
    }

    /* ------------------------------------------------------------------
     *  Featured / search / related
     * ----------------------------------------------------------------*/

    /**
     * Return featured published posts.
     */
    public static function featured(int $limit = 1): array
    {
        return Database::query(
            self::BASE_SELECT . "
            WHERE p.is_featured = 1
              AND p.status = 'published' AND p.published_at <= NOW()
            ORDER BY p.published_at DESC
            LIMIT :limit",
            ['limit' => $limit]
        )->fetchAll();
    }

    /**
     * Full-text search across title, excerpt, and body.
     */
    public static function search(string $query, int $limit, int $offset): array
    {
        return Database::query(
            self::BASE_SELECT . "
            WHERE MATCH(p.title, p.excerpt, p.content) AGAINST (:query IN BOOLEAN MODE)
              AND p.status = 'published' AND p.published_at <= NOW()
            ORDER BY p.published_at DESC
            LIMIT :limit OFFSET :offset",
            ['query' => $query, 'limit' => $limit, 'offset' => $offset]
        )->fetchAll();
    }

    /**
     * Count search results.
     */
    public static function countSearch(string $query): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM posts
             WHERE MATCH(title, excerpt, content) AGAINST (:query IN BOOLEAN MODE)
               AND status = 'published' AND published_at <= NOW()",
            ['query' => $query]
        )->fetch()['total'];
    }

    /**
     * Related posts based on same category, excluding the current post.
     */
    public static function related(int $postId, ?int $categoryId, int $limit = 3): array
    {
        if ($categoryId === null) {
            return Database::query(
                self::BASE_SELECT . "
                WHERE p.id != :post_id
                  AND p.status = 'published' AND p.published_at <= NOW()
                ORDER BY p.published_at DESC
                LIMIT :limit",
                ['post_id' => $postId, 'limit' => $limit]
            )->fetchAll();
        }

        return Database::query(
            self::BASE_SELECT . "
            WHERE p.id != :post_id
              AND p.category_id = :category_id
              AND p.status = 'published' AND p.published_at <= NOW()
            ORDER BY p.published_at DESC
            LIMIT :limit",
            ['post_id' => $postId, 'category_id' => $categoryId, 'limit' => $limit]
        )->fetchAll();
    }

    /* ------------------------------------------------------------------
     *  Admin listing (all statuses, filterable)
     * ----------------------------------------------------------------*/

    /**
     * Build WHERE clause and params from admin filter array.
     *
     * Supported filter keys: status, category_id, user_id, search
     */
    private static function buildAdminWhere(array $filters): array
    {
        $clauses = [];
        $params  = [];

        if (!empty($filters['status'])) {
            $clauses[]         = "p.status = :status";
            $params['status']  = $filters['status'];
        }

        if (!empty($filters['category_id'])) {
            $clauses[]              = "p.category_id = :category_id";
            $params['category_id']  = (int) $filters['category_id'];
        }

        if (!empty($filters['user_id'])) {
            $clauses[]          = "p.user_id = :user_id";
            $params['user_id']  = (int) $filters['user_id'];
        }

        if (!empty($filters['search'])) {
            $clauses[]          = "MATCH(p.title, p.excerpt, p.content) AGAINST (:search IN BOOLEAN MODE)";
            $params['search']   = $filters['search'];
        }

        $where = $clauses ? 'WHERE ' . implode(' AND ', $clauses) : '';

        return [$where, $params];
    }

    /**
     * Paginated admin list with optional filters.
     */
    public static function adminList(array $filters, int $limit, int $offset): array
    {
        [$where, $params] = self::buildAdminWhere($filters);

        $params['limit']  = $limit;
        $params['offset'] = $offset;

        return Database::query(
            self::BASE_SELECT . "
            {$where}
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset",
            $params
        )->fetchAll();
    }

    /**
     * Count admin list results with the same filters.
     */
    public static function adminCount(array $filters): int
    {
        [$where, $params] = self::buildAdminWhere($filters);

        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM posts p {$where}",
            $params
        )->fetch()['total'];
    }

    /* ------------------------------------------------------------------
     *  CUD operations
     * ----------------------------------------------------------------*/

    /**
     * Insert a new post and return the new ID.
     */
    public static function create(array $data): int
    {
        $columns      = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn(string $k) => ':' . $k, array_keys($data)));

        Database::query(
            "INSERT INTO posts ({$columns}) VALUES ({$placeholders})",
            $data
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Update an existing post by ID.
     */
    public static function update(int $id, array $data): void
    {
        $setClauses = implode(', ', array_map(fn(string $k) => "{$k} = :{$k}", array_keys($data)));
        $data['id'] = $id;

        Database::query(
            "UPDATE posts SET {$setClauses} WHERE id = :id",
            $data
        );
    }

    /**
     * Delete a post by ID (also removes pivot rows).
     */
    public static function delete(int $id): void
    {
        Database::query(
            "DELETE FROM post_tags WHERE post_id = :post_id",
            ['post_id' => $id]
        );

        Database::query(
            "DELETE FROM posts WHERE id = :id",
            ['id' => $id]
        );
    }

    /* ------------------------------------------------------------------
     *  Tag syncing (pivot table: post_tags)
     * ----------------------------------------------------------------*/

    /**
     * Sync the post_tags pivot table for a given post.
     * Removes all existing tag associations and re-inserts the provided ones.
     */
    public static function syncTags(int $postId, array $tagIds): void
    {
        Database::query(
            "DELETE FROM post_tags WHERE post_id = :post_id",
            ['post_id' => $postId]
        );

        foreach ($tagIds as $tagId) {
            Database::query(
                "INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)",
                ['post_id' => $postId, 'tag_id' => (int) $tagId]
            );
        }
    }

    /**
     * Get all tags attached to a post.
     */
    public static function getTags(int $postId): array
    {
        return Database::query(
            "SELECT t.*
             FROM tags t
             INNER JOIN post_tags pt ON pt.tag_id = t.id
             WHERE pt.post_id = :post_id
             ORDER BY t.name ASC",
            ['post_id' => $postId]
        )->fetchAll();
    }

    /* ------------------------------------------------------------------
     *  Misc helpers
     * ----------------------------------------------------------------*/

    /**
     * Increment the view counter for a post.
     */
    public static function incrementViews(int $postId): void
    {
        Database::query(
            "UPDATE posts SET view_count = view_count + 1 WHERE id = :id",
            ['id' => $postId]
        );
    }

    /**
     * Return year/month archive groups with post counts.
     */
    public static function archive(): array
    {
        return Database::query(
            "SELECT YEAR(published_at)  AS year,
                    MONTH(published_at) AS month,
                    COUNT(*)            AS post_count
             FROM posts
             WHERE status = 'published' AND published_at <= NOW()
             GROUP BY YEAR(published_at), MONTH(published_at)
             ORDER BY year DESC, month DESC"
        )->fetchAll();
    }
}
