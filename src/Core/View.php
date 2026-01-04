<?php

namespace App\Core;

class View
{
    private static array $shared = [];

    public static function render(string $view, array $data = []): string
    {
        $viewPath = self::getViewPath($view);
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: $view");
        }

        extract(array_merge(self::$shared, $data));
        
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        
        return $content;
    }

    public static function make(string $view, array $data = []): string
    {
        return self::render($view, $data);
    }

    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    public static function composer(string $view, callable $callback): void
    {
        // Placeholder for view composer functionality
    }

    private static function getViewPath(string $view): string
    {
        $view = str_replace('.', '/', $view);
        return view_path($view . '.php');
    }

    public static function exists(string $view): bool
    {
        $viewPath = self::getViewPath($view);
        return file_exists($viewPath);
    }
}

// Helper functions for views
if (!function_exists('view')) {
    function view(string $view, array $data = []): string
    {
        return \App\Core\View::render($view, $data);
    }
}

if (!function_exists('e')) {
    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('flash')) {
    function flash(string $key, mixed $default = null): mixed
    {
        return \App\Core\Session::getFlash($key, $default);
    }
}

if (!function_exists('errors')) {
    function errors(): array
    {
        return \App\Core\Session::getErrors();
    }
}

if (!function_exists('error')) {
    function error(string $key): ?string
    {
        $errors = errors();
        return $errors[$key] ?? null;
    }
}

if (!function_exists('has_error')) {
    function has_error(string $key): bool
    {
        return error($key) !== null;
    }
}