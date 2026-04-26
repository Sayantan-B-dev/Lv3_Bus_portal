<?php
/**
 * src/Core/Session.php
 * Session manager with flash message support.
 */

declare(strict_types=1);

namespace App\Core;

class Session
{
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }

    // ─── Flash Messages ───────────────────────────────────────────────────────

    /** Store a one-time flash message. */
    public static function flash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key] = $message;
    }

    /** Retrieve and clear a flash message. */
    public static function getFlash(string $key): ?string
    {
        $msg = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $msg;
    }

    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    // ─── Auth Helpers ─────────────────────────────────────────────────────────

    public static function setUser(array $user): void
    {
        self::set('admin_user', $user);
        self::set('admin_token', $user['_jwt'] ?? '');
    }

    public static function getUser(): ?array
    {
        return self::get('admin_user');
    }

    public static function isLoggedIn(): bool
    {
        return self::has('admin_user');
    }

    public static function logout(): void
    {
        self::forget('admin_user');
        self::forget('admin_token');
    }
}
