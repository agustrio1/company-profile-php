<?php

namespace App\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $config = config('app.session');
            
            session_set_cookie_params([
                'lifetime' => $config['lifetime'] * 60,
                'path' => $config['cookie_path'],
                'domain' => $config['cookie_domain'],
                'secure' => $config['cookie_secure'],
                'httponly' => $config['cookie_httponly'],
                'samesite' => $config['cookie_samesite']
            ]);
            
            session_name($config['cookie_name']);
            session_start();
        }
    }

    public static function put(string $key, mixed $value): void
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

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function all(): array
    {
        return $_SESSION;
    }

    public static function flush(): void
    {
        $_SESSION = [];
    }

    public static function destroy(): void
    {
        session_destroy();
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        
        return $value;
    }

    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    public static function clearFlash(): void
    {
        unset($_SESSION['_flash']);
    }

    public static function getErrors(): array
    {
        $errors = $_SESSION['_errors'] ?? [];
        unset($_SESSION['_errors']);
        
        return $errors;
    }

    // FIX: Explicitly mark parameter as nullable
    public static function getOldInput(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            $old = $_SESSION['_old'] ?? [];
            unset($_SESSION['_old']);
            return $old;
        }
        
        $value = $_SESSION['_old'][$key] ?? $default;
        
        return $value;
    }

    public static function clearOldInput(): void
    {
        unset($_SESSION['_old']);
    }
}