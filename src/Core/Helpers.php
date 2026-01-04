<?php

/**
 * Environment Helper
 */
function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

/**
 * Config Helper
 */
function config(string $key, mixed $default = null): mixed
{
    $keys = explode('.', $key);
    $file = array_shift($keys);
    
    $config = require config_path($file . '.php');
    
    foreach ($keys as $segment) {
        if (!isset($config[$segment])) {
            return $default;
        }
        $config = $config[$segment];
    }
    
    return $config;
}

/**
 * Path Helpers
 */
function base_path(string $path = ''): string
{
    $basePath = dirname(__DIR__, 2);
    return $path ? $basePath . '/' . ltrim($path, '/') : $basePath;
}

function config_path(string $path = ''): string
{
    return base_path('src/Config/' . ltrim($path, '/'));
}

function public_path(string $path = ''): string
{
    return base_path('public/' . ltrim($path, '/'));
}

function storage_path(string $path = ''): string
{
    return base_path('storage/' . ltrim($path, '/'));
}

function resource_path(string $path = ''): string
{
    return base_path('resources/' . ltrim($path, '/'));
}

function view_path(string $path = ''): string
{
    return base_path('resources/views/' . ltrim($path, '/'));
}

/**
 * URL Helpers
 */
function asset(string $path): string
{
    return rtrim(config('app.url'), '/') . '/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    return rtrim(config('app.url'), '/') . '/' . ltrim($path, '/');
}

function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

function back(): void
{
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    redirect($referer);
}

/**
 * Debug Helpers
 */
function dd(...$vars): void
{
    foreach ($vars as $var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    die();
}

function dump(...$vars): void
{
    foreach ($vars as $var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}

/**
 * Session Helpers
 */
function old(string $key, mixed $default = null): mixed
{
    return $_SESSION['_old'][$key] ?? $_SESSION['old'][$key] ?? $default;
}

function session(?string $key = null, mixed $default = null): mixed
{
    if ($key === null) {
        return $_SESSION;
    }
    
    return $_SESSION['flash'][$key] ?? $_SESSION[$key] ?? $default;
}

/**
 * Validation Error Helpers
 */
if (!function_exists('hasError')) {
    function hasError(string $key): bool
    {
        return isset($_SESSION['errors'][$key]);
    }
}

if (!function_exists('error')) {
    function error(string $key): ?string
    {
        return $_SESSION['errors'][$key] ?? null;
    }
}

if (!function_exists('errors')) {
    function errors(): array
    {
        return $_SESSION['errors'] ?? [];
    }
}

/**
 * CSRF Helpers
 */
function csrf_token(): string
{
    if (!isset($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
}

function method_field(string $method): string
{
    return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
}

/**
 * String Helpers
 */
function slugify(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    
    return $text ?: 'n-a';
}

function str_limit(string $value, int $limit = 100, string $end = '...'): string
{
    if (mb_strlen($value) <= $limit) {
        return $value;
    }
    
    return mb_substr($value, 0, $limit) . $end;
}

/**
 * Date Helpers
 */
function now(): string
{
    return date('Y-m-d H:i:s');
}

function format_date(string $date, string $format = 'd M Y'): string
{
    return date($format, strtotime($date));
}

/**
 * Vite Assets Helper
 */
function vite(): string
{
    return \App\Core\Vite::assets();
}

/**
 * Get authenticated user
 */
if (!function_exists('auth')) {
    function auth(): object
    {
        return new class {
            public function user(): ?object
            {
                return $_SESSION['auth_user'] ?? null;
            }
            
            public function check(): bool
            {
                return isset($_SESSION['auth_user']);
            }
            
            public function id(): ?string
            {
                return $_SESSION['auth_user']->id ?? null;
            }
            
            public function guest(): bool
            {
                return !$this->check();
            }
        };
    }
}

/**
 * Check if user is authenticated
 */
if (!function_exists('is_authenticated')) {
    function is_authenticated(): bool
    {
        return isset($_SESSION['auth_user']);
    }
}

/**
 * Get current authenticated user
 */
if (!function_exists('current_user')) {
    function current_user(): ?object
    {
        return $_SESSION['auth_user'] ?? null;
    }
}