<?php
/**
 * src/Core/Database.php
 * PDO Singleton — exactly as specified in SRS Section 7.2.
 */

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Return the single shared PDO connection.
     * Creates it on first call using .env credentials.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $_ENV['DB_HOST'],
                $_ENV['DB_PORT'],
                $_ENV['DB_NAME'],
                $_ENV['DB_CHARSET'] ?? 'utf8mb4'
            );

            try {
                self::$instance = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'] ?? '', [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                error_log('DB Connection failed: ' . $e->getMessage());
                http_response_code(503);
                die(json_encode([
                    'status'  => 'error',
                    'message' => 'Database service unavailable. Please try again later.',
                ]));
            }
        }

        return self::$instance;
    }

    // Prevent instantiation and cloning — singleton pattern
    private function __construct() {}
    private function __clone() {}
}
