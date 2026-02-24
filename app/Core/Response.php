<?php

namespace App\Core;

class Response
{
    public static function redirect(string $url, int $code = 302): void
    {
        header("Location: $url", true, $code);
        exit;
    }

    public static function setStatus(int $code): void
    {
        http_response_code($code);
    }

    public static function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function xml(string $content): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        echo $content;
        exit;
    }

    public static function setHeader(string $name, string $value): void
    {
        header("$name: $value");
    }

    public static function notFound(): void
    {
        http_response_code(404);
        $view = new View();
        echo $view->render('frontend/404');
        exit;
    }
}
