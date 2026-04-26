<?php
declare(strict_types=1);
namespace App\Middleware;

class AdminMiddleware
{
    public static function handle(): array
    {
        return AuthMiddleware::requireAdmin();
    }
}
