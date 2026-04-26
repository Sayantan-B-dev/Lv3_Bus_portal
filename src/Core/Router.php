<?php
/**
 * src/Core/Router.php
 * Front controller router — parses the URL and dispatches to controllers.
 * Supports GET and POST methods with named parameters: {id}, {routeId}, etc.
 */

declare(strict_types=1);

namespace App\Core;

class Router
{
    /** @var array<int, array{method:string, pattern:string, handler:callable|array}> */
    private array $routes = [];

    // ─── Route Registration ───────────────────────────────────────────────────

    public function get(string $pattern, callable|array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function put(string $pattern, callable|array $handler): void
    {
        $this->add('PUT', $pattern, $handler);
    }

    public function delete(string $pattern, callable|array $handler): void
    {
        $this->add('DELETE', $pattern, $handler);
    }

    private function add(string $method, string $pattern, callable|array $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    // ─── Dispatch ─────────────────────────────────────────────────────────────

    /**
     * Resolve the current HTTP request to a handler and call it.
     * Passes extracted URL parameters as arguments to the handler.
     */
    public function dispatch(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri    = $this->getUri();

        // Support method override via hidden _method field (for HTML forms)
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['pattern'], $uri);
            if ($params !== null) {
                $this->call($route['handler'], $params);
                return;
            }
        }

        // 404 — no matching route
        $this->notFound();
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Extract the URI path, stripping base path and query string.
     */
    private function getUri(): string
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $basePath   = parse_url(APP_URL, PHP_URL_PATH) ?? '';

        // Strip base path prefix
        $uri = '/' . ltrim(substr($requestUri, strlen($basePath)), '/');

        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        return rtrim($uri, '/') ?: '/';
    }

    /**
     * Match a route pattern against a URI.
     * Returns array of named params on match, null otherwise.
     *
     * Pattern syntax: /routes/{id}/edit
     */
    private function match(string $pattern, string $uri): ?array
    {
        // Convert {param} placeholders to named regex groups
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $uri, $matches)) {
            return null;
        }

        // Return only named string keys (filter out numeric keys)
        return array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
    }

    /**
     * Invoke a handler — supports [ControllerClass, 'method'] arrays and callables.
     */
    private function call(callable|array $handler, array $params): void
    {
        if (is_array($handler) && is_string($handler[0])) {
            [$class, $method] = $handler;
            $controller = new $class();
            $controller->$method($params);
        } elseif (is_callable($handler)) {
            $handler($params);
        } else {
            throw new \RuntimeException('Invalid route handler.');
        }
    }

    private function notFound(): void
    {
        http_response_code(404);
        if ($this->isApiRequest()) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Route not found', 'code' => 404]);
        } else {
            // Show a simple 404 page
            echo '<!DOCTYPE html><html><head><title>404 — Not Found</title>
            <style>body{background:#0D0D0D;color:#E8E8E8;font-family:sans-serif;text-align:center;padding-top:100px}
            h1{color:#E8B84B;font-size:3rem}a{color:#E8B84B}</style></head>
            <body><h1>404</h1><p>Page not found.</p><a href="' . APP_URL . '">← Back to Home</a></body></html>';
        }
    }

    private function isApiRequest(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $uri    = $_SERVER['REQUEST_URI'] ?? '';
        $base   = parse_url(APP_URL, PHP_URL_PATH) ?? '';
        $cleanUri = '/' . ltrim(substr($uri, strlen($base)), '/');
        
        return str_contains($accept, 'application/json') || 
               str_starts_with($cleanUri, '/api');
    }
}
