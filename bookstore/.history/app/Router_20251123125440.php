<?php

declare(strict_types=1);

namespace App;

class Router
{
    /** @var array<string, array<string, callable|array{0:string,1:string}>> */
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = $this->normalizePath($path);

        if (!isset($this->routes[$method])) {
            $this->sendNotFound();
            return;
        }

        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = $this->convertRouteToPattern($route);
            if (preg_match($pattern, $path, $matches)) {
                $params = $this->extractParams($matches);
                $this->invokeHandler($handler, $params);
                return;
            }
        }

        $this->sendNotFound();
    }

    private function normalizePath(string $path): string
    {
        $path = $path === '' ? '/' : $path;

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $trimmed = substr($path, strlen($basePath));
            $path = $trimmed === '' ? '/' : $trimmed;
        }

        // Remove trailing slash except for root
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/') ?: '/';
        }

        return $path;
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $method = strtoupper($method);
        $this->routes[$method][$path] = $handler;
    }

    private function convertRouteToPattern(string $route): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $route);
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    /**
     * @param array<int|string, string> $matches
     * @return array<string,string>
     */
    private function extractParams(array $matches): array
    {
        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }
        return $params;
    }

    /**
     * @param callable|array{0:string,1:string} $handler
     * @param array<string,string> $params
     */
    private function invokeHandler(callable|array $handler, array $params): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            if (!class_exists($class)) {
                $this->sendNotFound();
                return;
            }
            $instance = new $class();
            $handler = [$instance, $method];
        }

        call_user_func($handler, $params);
    }

    private function sendNotFound(): void
    {
        http_response_code(404);
        echo '404 Not Found';
    }
}