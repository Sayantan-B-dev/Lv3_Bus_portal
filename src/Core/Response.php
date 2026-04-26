<?php
/**
 * src/Core/Response.php
 * HTTP response helpers — JSON, redirects, view rendering.
 */

declare(strict_types=1);

namespace App\Core;

class Response
{
    /**
     * Emit a JSON success response (SRS 6.4 format).
     */
    public static function json(mixed $data, int $status = 200, array $meta = []): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        $body = ['status' => 'success', 'data' => $data];
        if (!empty($meta)) {
            $body['meta'] = $meta;
        }
        echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Emit a JSON error response.
     */
    public static function error(string $message, int $status = 400): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status'  => 'error',
            'message' => $message,
            'code'    => $status,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * HTTP redirect.
     */
    public static function redirect(string $url, int $status = 302): never
    {
        http_response_code($status);
        header('Location: ' . $url);
        exit;
    }

    /**
     * Render a PHP view file with extracted variables.
     *
     * @param string $view  Path relative to views/, e.g. 'home/index'
     * @param array  $data  Variables to extract into view scope
     */
    public static function view(string $view, array $data = []): void
    {
        $file = BASE_PATH . '/views/' . $view . '.php';
        if (!file_exists($file)) {
            self::error("View not found: {$view}", 500);
        }
        extract($data, EXTR_SKIP);
        require $file;
    }
}
