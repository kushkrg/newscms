<?php

namespace App\Models;

use App\Core\Database;

class Subscriber
{
    /**
     * Find a subscriber by primary key.
     */
    public static function find(int $id): ?array
    {
        $row = Database::query(
            "SELECT * FROM subscribers WHERE id = :id LIMIT 1",
            ['id' => $id]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Find a subscriber by email address.
     */
    public static function findByEmail(string $email): ?array
    {
        $row = Database::query(
            "SELECT * FROM subscribers WHERE email = :email LIMIT 1",
            ['email' => $email]
        )->fetch();

        return $row ?: null;
    }

    /**
     * Return a paginated list of subscribers.
     */
    public static function all(int $limit, int $offset, string $status = '', string $search = ''): array
    {
        $where = [];
        $params = [];

        if ($status !== '') {
            $where[] = "status = :status";
            $params['status'] = $status;
        }

        if ($search !== '') {
            $where[] = "(email LIKE :search OR name LIKE :search2)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $params['limit'] = $limit;
        $params['offset'] = $offset;

        return Database::query(
            "SELECT * FROM subscribers {$whereClause} ORDER BY created_at DESC LIMIT :limit OFFSET :offset",
            $params
        )->fetchAll();
    }

    /**
     * Count total subscribers (with optional filtering).
     */
    public static function count(string $status = '', string $search = ''): int
    {
        $where = [];
        $params = [];

        if ($status !== '') {
            $where[] = "status = :status";
            $params['status'] = $status;
        }

        if ($search !== '') {
            $where[] = "(email LIKE :search OR name LIKE :search2)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM subscribers {$whereClause}",
            $params
        )->fetch()['total'];
    }

    /**
     * Count active subscribers.
     */
    public static function activeCount(): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) AS total FROM subscribers WHERE status = 'active'"
        )->fetch()['total'];
    }

    /**
     * Get all active subscriber emails (for sending mail).
     */
    public static function activeEmails(): array
    {
        return Database::query(
            "SELECT id, email, name FROM subscribers WHERE status = 'active' ORDER BY email ASC"
        )->fetchAll();
    }

    /**
     * Subscribe a new email.
     */
    public static function subscribe(string $email, string $name = ''): int
    {
        $existing = self::findByEmail($email);

        if ($existing) {
            // Re-activate if previously unsubscribed
            if ($existing['status'] === 'unsubscribed') {
                Database::query(
                    "UPDATE subscribers SET status = 'active', name = :name, subscribed_at = NOW(), unsubscribed_at = NULL WHERE id = :id",
                    ['name' => $name ?: $existing['name'], 'id' => $existing['id']]
                );
            }
            return (int) $existing['id'];
        }

        Database::query(
            "INSERT INTO subscribers (email, name, status, subscribed_at) VALUES (:email, :name, 'active', NOW())",
            ['email' => $email, 'name' => $name]
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Update a subscriber.
     */
    public static function update(int $id, array $data): void
    {
        $setClauses = implode(', ', array_map(fn(string $k) => "{$k} = :{$k}", array_keys($data)));
        $data['id'] = $id;

        Database::query(
            "UPDATE subscribers SET {$setClauses} WHERE id = :id",
            $data
        );
    }

    /**
     * Delete a subscriber by ID.
     */
    public static function delete(int $id): void
    {
        Database::query(
            "DELETE FROM subscribers WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Unsubscribe by email.
     */
    public static function unsubscribe(string $email): bool
    {
        $sub = self::findByEmail($email);
        if (!$sub) return false;

        Database::query(
            "UPDATE subscribers SET status = 'unsubscribed', unsubscribed_at = NOW() WHERE id = :id",
            ['id' => $sub['id']]
        );

        return true;
    }

    /**
     * Log a sent email.
     */
    public static function logEmail(string $subject, string $body, int $recipientCount, int $sentBy): int
    {
        Database::query(
            "INSERT INTO email_logs (subject, body, recipient_count, sent_by) VALUES (:subject, :body, :count, :sent_by)",
            ['subject' => $subject, 'body' => $body, 'count' => $recipientCount, 'sent_by' => $sentBy]
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Get email logs.
     */
    public static function emailLogs(int $limit = 20, int $offset = 0): array
    {
        return Database::query(
            "SELECT el.*, u.name AS sender_name
             FROM email_logs el
             LEFT JOIN users u ON el.sent_by = u.id
             ORDER BY el.sent_at DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        )->fetchAll();
    }
}
