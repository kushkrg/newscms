<?php

return [
    'app_name'       => env('APP_NAME', 'NewsCMS'),
    'app_url'        => env('APP_URL', 'http://localhost:8000'),
    'app_env'        => env('APP_ENV', 'development'),
    'app_debug'      => env('APP_DEBUG', 'false') === 'true',

    'db' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'name' => env('DB_NAME', 'news_cms'),
        'user' => env('DB_USER', 'root'),
        'pass' => env('DB_PASS', ''),
    ],

    'smtp' => [
        'host'      => env('SMTP_HOST', ''),
        'port'      => (int) env('SMTP_PORT', '587'),
        'user'      => env('SMTP_USER', ''),
        'pass'      => env('SMTP_PASS', ''),
        'from'      => env('SMTP_FROM', 'noreply@example.com'),
        'from_name' => env('SMTP_FROM_NAME', 'NewsCMS'),
    ],

    'upload' => [
        'max_size'      => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
        'image_sizes'   => [
            'medium' => 800,
            'thumb'  => 400,
        ],
    ],

    'posts_per_page'  => 12,
    'session_lifetime' => 7200, // 2 hours
];
