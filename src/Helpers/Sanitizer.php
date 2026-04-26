<?php
declare(strict_types=1);
namespace App\Helpers;

class Sanitizer
{
    public static function string(mixed $value): string
    {
        return htmlspecialchars(trim((string)$value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function int(mixed $value, int $default = 0): int
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false
            ? (int)$value
            : $default;
    }

    public static function float(mixed $value, float $default = 0.0): float
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false
            ? (float)$value
            : $default;
    }

    public static function email(mixed $value): string
    {
        return filter_var(trim((string)$value), FILTER_SANITIZE_EMAIL) ?: '';
    }

    public static function url(mixed $value): string
    {
        return filter_var(trim((string)$value), FILTER_SANITIZE_URL) ?: '';
    }

    /** Strip all HTML tags. */
    public static function strip(mixed $value): string
    {
        return strip_tags(trim((string)$value));
    }
}
