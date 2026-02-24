<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $params = [];

    public function get(string $pattern, array|string $controller, ?string $action = null): self
    {
        [$ctrl, $act] = $this->resolveHandler($controller, $action);
        $this->addRoute('GET', $pattern, $ctrl, $act);
        return $this;
    }

    public function post(string $pattern, array|string $controller, ?string $action = null): self
    {
        [$ctrl, $act] = $this->resolveHandler($controller, $action);
        $this->addRoute('POST', $pattern, $ctrl, $act);
        return $this;
    }

    public function any(string $pattern, array|string $controller, ?string $action = null): self
    {
        [$ctrl, $act] = $this->resolveHandler($controller, $action);
        $this->addRoute('GET', $pattern, $ctrl, $act);
        $this->addRoute('POST', $pattern, $ctrl, $act);
        return $this;
    }

    private function resolveHandler(array|string $controller, ?string $action): array
    {
        if (is_array($controller)) {
            return [$controller[0], $controller[1]];
        }
        return [$controller, $action ?? 'index'];
    }

    private function addRoute(string $method, string $pattern, string $controller, string $action): void
    {
        // Convert route pattern to regex
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        $this->routes[] = [
            'method'     => $method,
            'pattern'    => $regex,
            'controller' => $controller,
            'action'     => $action,
        ];
    }

    public function dispatch(Request $request): void
    {
        $uri = $request->uri();
        $method = $request->method();

        // Check redirects first
        $this->checkRedirects($uri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->params = $params;

                $controllerClass = $route['controller'];
                $action = $route['action'];

                if (!class_exists($controllerClass)) {
                    throw new \RuntimeException("Controller not found: $controllerClass");
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $action)) {
                    throw new \RuntimeException("Action $action not found in $controllerClass");
                }

                // Run middleware if controller has it
                if (method_exists($controller, 'middleware')) {
                    $controller->middleware();
                }

                $controller->$action($request, $params);
                return;
            }
        }

        // No route matched
        Response::notFound();
    }

    private function checkRedirects(string $uri): void
    {
        try {
            $stmt = Database::query(
                "SELECT to_url, type FROM redirects WHERE from_path = ? AND is_active = 1 LIMIT 1",
                [$uri]
            );
            $redirect = $stmt->fetch();
            if ($redirect) {
                Database::query(
                    "UPDATE redirects SET hit_count = hit_count + 1 WHERE from_path = ?",
                    [$uri]
                );
                Response::redirect($redirect['to_url'], (int) $redirect['type']);
            }
        } catch (\Exception $e) {
            // Silently fail - redirects table might not exist yet
        }
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
