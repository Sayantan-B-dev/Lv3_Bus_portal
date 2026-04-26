<?php
declare(strict_types=1);
namespace App\Middleware;

class RateLimitMiddleware
{
    /**
     * Simple file-based rate limiter.
     * Allows $maxRequests per $windowSeconds per IP per $route.
     */
    public static function check(string $route = 'default', int $maxRequests = 60, int $windowSeconds = 60): void
    {
        $ip    = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $key   = md5($ip . $route);
        $file  = BASE_PATH . '/storage/logs/rl_' . $key . '.json';

        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $now  = time();

        // Purge old entries outside window
        $data = array_filter($data, fn($t) => $t > $now - $windowSeconds);

        if (count($data) >= $maxRequests) {
            http_response_code(429);
            header('Retry-After: ' . $windowSeconds);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Too many requests', 'code' => 429]);
            exit;
        }

        $data[] = $now;
        file_put_contents($file, json_encode(array_values($data)));
    }
}
