<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Message
{
    /**
     * Create a new message record.
     */
    public static function create(array $data): int
    {
        Database::query(
            "INSERT INTO messages (name, email, subject, message, status, ip_address, created_at)
             VALUES (:name, :email, :subject, :message, :status, :ip_address, :created_at)",
            [
                'name'       => $data['name'],
                'email'      => $data['email'],
                'subject'    => $data['subject'],
                'message'    => $data['message'],
                'status'     => $data['status'] ?? 'unread',
                'ip_address' => $data['ip_address'] ?? null,
                'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s'),
            ]
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Find a single message by ID.
     */
    public static function find(int $id): ?array
    {
        $row = Database::query(
            "SELECT m.*, u.name AS replied_by_name
             FROM messages m
             LEFT JOIN users u ON u.id = m.replied_by
             WHERE m.id = :id
             LIMIT 1",
            ['id' => $id]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Count messages by status.
     */
    public static function countByStatus(?string $status = null): int
    {
        if ($status) {
            $row = Database::query(
                "SELECT COUNT(*) AS cnt FROM messages WHERE status = :status",
                ['status' => $status]
            )->fetch();
        } else {
            $row = Database::query("SELECT COUNT(*) AS cnt FROM messages")->fetch();
        }

        return (int) ($row['cnt'] ?? 0);
    }

    /**
     * Count unread messages (used in nav badge).
     */
    public static function unreadCount(): int
    {
        return self::countByStatus('unread');
    }

    /**
     * Paginated list of messages with optional status filter.
     */
    public static function paginate(?string $status, int $limit, int $offset): array
    {
        $where = '';
        $params = [];

        if ($status) {
            $where = 'WHERE status = :status';
            $params['status'] = $status;
        }

        $params['limit']  = $limit;
        $params['offset'] = $offset;

        return Database::query(
            "SELECT * FROM messages {$where} ORDER BY created_at DESC LIMIT :limit OFFSET :offset",
            $params
        )->fetchAll();
    }

    /**
     * Update message status.
     */
    public static function updateStatus(int $id, string $status): void
    {
        Database::query(
            "UPDATE messages SET status = :status WHERE id = :id",
            ['status' => $status, 'id' => $id]
        );
    }

    /**
     * Store reply details on the message.
     */
    public static function saveReply(int $id, string $replyText, int $repliedBy): void
    {
        Database::query(
            "UPDATE messages
             SET reply_text = :reply_text,
                 replied_at = :replied_at,
                 replied_by = :replied_by,
                 status     = 'replied'
             WHERE id = :id",
            [
                'reply_text' => $replyText,
                'replied_at' => date('Y-m-d H:i:s'),
                'replied_by' => $repliedBy,
                'id'         => $id,
            ]
        );
    }

    /**
     * Delete a message.
     */
    public static function delete(int $id): void
    {
        Database::query("DELETE FROM messages WHERE id = :id", ['id' => $id]);
    }
}
