<?php
/**
 * UPTEC - Sistema de Control de Cursos v2.0
 * Modulo de Sanitizacion de Datos - OWASP XSS Prevention
 */

declare(strict_types=1);

if (!defined('UPTEC_ACCESS')) {
    define('UPTEC_ACCESS', true);
}

class Sanitizer
{
    public static function html(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function attr(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function js(string $text): string
    {
        return str_replace(['\\', "'", '"', "\n", "\r", '&', '<', '>', '/'],
                           ['\\\\', "\\'", '\\"', '\\n', '\\r', '\\x26', '\\x3c', '\\x3e', '\\x2f'],
                           $text);
    }

    public static function email(string $email): string
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    public static function trim(string $text): string
    {
        return trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $text));
    }

    public static function int($value): ?int
    {
        $filtered = filter_var($value, FILTER_VALIDATE_INT);
        return ($filtered !== false) ? $filtered : null;
    }

    public static function float($value): ?float
    {
        $filtered = filter_var($value, FILTER_VALIDATE_FLOAT);
        return ($filtered !== false) ? $filtered : null;
    }

    public static function bool($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }

    public static function like(string $text): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $text);
    }

    public static function input(string $key, string $method = 'request', ?string $default = null): ?string
    {
        $value = null;

        switch (strtolower($method)) {
            case 'get':
                $value = $_GET[$key] ?? $default;
                break;
            case 'post':
                $value = $_POST[$key] ?? $default;
                break;
            case 'request':
            default:
                $value = $_REQUEST[$key] ?? $default;
                break;
        }

        if ($value === null) {
            return null;
        }

        return self::html(self::trim((string)$value));
    }

    /**
     * Sanitiza array completo recursivamente
     */
    public static function clean(array $data): array
    {
        $clean = [];
        foreach ($data as $key => $value) {
            $cleanKey = is_string($key) ? self::html($key) : $key;
            if (is_array($value)) {
                $clean[$cleanKey] = self::clean($value);
            } elseif (is_string($value)) {
                $clean[$cleanKey] = self::trim($value);
            } else {
                $clean[$cleanKey] = $value;
            }
        }
        return $clean;
    }
}
