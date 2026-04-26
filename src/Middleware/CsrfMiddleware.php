<?php
declare(strict_types=1);
namespace App\Middleware;

class CsrfMiddleware
{
    public static function generateToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validate(): void
    {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die(json_encode(['status' => 'error', 'message' => 'CSRF token mismatch']));
        }
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::generateToken()) . '">';
    }
}
