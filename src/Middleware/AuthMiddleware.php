<?php
declare(strict_types=1);
namespace App\Middleware;
use App\Services\AuthService;

class AuthMiddleware
{
    public static function handle(): array
    {
        $authService = new AuthService();
        $token = null;

        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (str_starts_with($header, 'Bearer ')) {
            $token = substr($header, 7);
        }
        if (!$token && isset($_SESSION['admin_token'])) {
            $token = $_SESSION['admin_token'];
        }
        if (!$token) { self::unauthorized(); }

        $payload = $authService->validateJWT($token);
        if (!$payload) { self::unauthorized(); }

        return $payload;
    }

    public static function requireAdmin(): array
    {
        $payload = self::handle();
        
        // Always fetch full user from DB to ensure we have name, avatar, etc.
        $user = (new \App\Models\User())->findById((int)$payload['sub']);
        
        if (!$user || !in_array($user['role'] ?? '', ['admin', 'super_admin'], true)) {
            self::forbidden();
        }

        // Sync session if needed
        $sessionUser = \App\Core\Session::getUser();
        if (!$sessionUser || ($sessionUser['role'] ?? '') !== $user['role']) {
             \App\Core\Session::setUser($user);
        }

        return $user;
    }

    private static function unauthorized(): never
    {
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized', 'code' => 401]);
        } else {
            header('Location: ' . APP_URL . '/auth/login');
        }
        exit;
    }

    private static function forbidden(): never
    {
        http_response_code(403);
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Forbidden', 'code' => 403]);
        } else {
            echo '<h1>403 Forbidden</h1><p>You do not have permission to access this page.</p>';
        }
        exit;
    }
}
