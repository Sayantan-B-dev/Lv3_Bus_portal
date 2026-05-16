<?php
/**
 * config/config.php
 * Loads .env and bootstraps the application environment.
 * Called once from public/index.php and api/index.php.
 */

declare(strict_types=1);

// ─── Autoloader ──────────────────────────────────────────────────────────────
$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    http_response_code(503);
    die('<h1>Composer dependencies not installed.</h1><p>Run <code>composer install</code> in the project root.</p>');
}
require_once $autoload;

// ─── Load .env ───────────────────────────────────────────────────────────────
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Required keys — throws if missing
$dotenv->required([
    'APP_NAME', 'APP_ENV', 'APP_URL', 'APP_SECRET',
    'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER',
    'JWT_SECRET', 'JWT_EXPIRY',
])->notEmpty();

// ─── PHP Settings ────────────────────────────────────────────────────────────
$appEnv = $_ENV['APP_ENV'] ?? 'production';

if ($appEnv === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Log errors to storage/logs/
$logDir = dirname(__DIR__) . '/storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0775, true);
}
ini_set('log_errors', '1');
ini_set('error_log', $logDir . '/app.log');

// ─── Session ─────────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200),
        'path'     => '/',
        'secure'   => ($appEnv === 'production'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('YATRAPATH_SESS');
    session_start();
}

// ─── Timezone ─────────────────────────────────────────────────────────────────
date_default_timezone_set('Asia/Kolkata');

// ─── Define Root Constants ───────────────────────────────────────────────────
define('BASE_PATH', dirname(__DIR__));
define('APP_URL',   rtrim($_ENV['APP_URL'], '/'));

// ─── Error Handling ──────────────────────────────────────────────────────────
if ($appEnv === 'production') {
    set_exception_handler(function ($e) {
        error_log($e->getMessage() . "\n" . $e->getTraceAsString());
        http_response_code(500);
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
            echo json_encode(['status' => 'error', 'message' => 'Internal Server Error']);
        } else {
            require BASE_PATH . '/views/errors/500.php';
        }
        exit;
    });

    set_error_handler(function ($level, $message, $file, $line) {
        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    });
}
