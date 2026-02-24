<?php

declare(strict_types=1);

// Base paths
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('VIEW_PATH', BASE_PATH . '/views');
define('CONFIG_PATH', BASE_PATH . '/config');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Load .env
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Helper to get env values
function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

// PSR-4 Autoloader
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) return;
    $relativeClass = substr($class, strlen($prefix));
    $file = APP_PATH . '/' . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Error handling
$isDebug = env('APP_DEBUG', 'false') === 'true';

if ($isDebug) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_PATH . '/logs/app.log');
}

set_exception_handler(function (Throwable $e) use ($isDebug) {
    $log = date('Y-m-d H:i:s') . " [ERROR] " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n\n";
    @file_put_contents(STORAGE_PATH . '/logs/app.log', $log, FILE_APPEND);

    if ($isDebug) {
        echo "<h1>Error</h1><pre>" . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        http_response_code(500);
        if (file_exists(VIEW_PATH . '/frontend/500.php')) {
            include VIEW_PATH . '/frontend/500.php';
        } else {
            echo '<h1>500 - Internal Server Error</h1>';
        }
    }
    exit;
});

// Load config
$config = require CONFIG_PATH . '/config.php';

// HTML escape helper
function h(string|null $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Asset URL helper
function asset(string $path): string
{
    return rtrim(env('APP_URL', ''), '/') . '/assets/' . ltrim($path, '/');
}

// Full URL helper
function url(string $path = ''): string
{
    return rtrim(env('APP_URL', ''), '/') . '/' . ltrim($path, '/');
}

// Uploaded file URL helper
function upload_url(string $path): string
{
    return rtrim(env('APP_URL', ''), '/') . '/uploads/' . ltrim($path, '/');
}
