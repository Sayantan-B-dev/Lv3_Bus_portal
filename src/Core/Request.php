<?php
/**
 * src/Core/Request.php
 * HTTP request abstraction — wraps superglobals with sanitization helpers.
 */

declare(strict_types=1);

namespace App\Core;

class Request
{
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function isPost(): bool   { return $this->method() === 'POST'; }
    public function isGet(): bool    { return $this->method() === 'GET'; }
    public function isDelete(): bool { return $this->method() === 'DELETE'; }
    public function isPut(): bool    { return $this->method() === 'PUT'; }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public function all(): array
    {
        if ($this->isJson()) {
            return $this->json();
        }
        return array_merge($_GET, $_POST);
    }

    public function json(): array
    {
        $body = file_get_contents('php://input');
        if (empty($body)) return [];
        $data = json_decode($body, true);
        return is_array($data) ? $data : [];
    }

    public function isJson(): bool
    {
        $ct = $_SERVER['CONTENT_TYPE'] ?? '';
        return str_contains($ct, 'application/json');
    }

    public function header(string $name): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$key] ?? null;
    }

    public function bearerToken(): ?string
    {
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (str_starts_with($auth, 'Bearer ')) {
            return substr($auth, 7);
        }
        return null;
    }

    public function ip(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '0.0.0.0';
    }

    /** Return sanitized string input */
    public function input(string $key, string $default = ''): string
    {
        $value = $this->all()[$key] ?? $default;
        return htmlspecialchars(trim((string)$value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /** Return integer input */
    public function int(string $key, int $default = 0): int
    {
        return (int)($this->all()[$key] ?? $default);
    }
}
