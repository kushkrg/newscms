<?php

namespace App\Models;

use App\Core\Database;

class Setting
{
    /**
     * Get all settings as a flat key => value array.
     */
    public static function getAll(): array
    {
        $rows = Database::query(
            "SELECT key_name, value FROM settings"
        )->fetchAll();

        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key_name']] = $row['value'];
        }

        return $settings;
    }

    /**
     * Get a single setting value by its key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $row = Database::query(
            "SELECT value FROM settings WHERE key_name = :key LIMIT 1",
            ['key' => $key]
        )->fetch();

        return $row ? $row['value'] : $default;
    }

    /**
     * Insert or update a single setting.
     */
    public static function set(string $key, mixed $value): void
    {
        Database::query(
            "INSERT INTO settings (key_name, value)
             VALUES (:key, :value)
             ON DUPLICATE KEY UPDATE value = :value_update",
            [
                'key'          => $key,
                'value'        => $value,
                'value_update' => $value,
            ]
        );
    }

    /**
     * Get all settings that belong to a given group.
     */
    public static function getByGroup(string $group): array
    {
        $rows = Database::query(
            "SELECT key_name, value
             FROM settings
             WHERE group_name = :group",
            ['group' => $group]
        )->fetchAll();

        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key_name']] = $row['value'];
        }

        return $settings;
    }
}
