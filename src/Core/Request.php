<?php

namespace App\Core;

class Request
{
    private array $queryParams;
    private array $bodyParams;
    private array $serverParams;
    private array $files;

    public function __construct()
    {
        $this->queryParams = $_GET;
        $this->bodyParams = $_POST;
        $this->serverParams = $_SERVER;
        $this->files = $_FILES;
    }

    public function method(): string
    {
        $method = $this->serverParams['REQUEST_METHOD'];
        
        if ($method === 'POST' && $this->has('_method')) {
            $method = strtoupper($this->input('_method'));
        }
        
        return $method;
    }

    public function uri(): string
    {
        return strtok($this->serverParams['REQUEST_URI'], '?');
    }

    public function path(): string
    {
        $path = $this->uri();
        return $path === '' ? '/' : $path;
    }

    public function url(): string
    {
        $scheme = $this->serverParams['REQUEST_SCHEME'] ?? 
                  (isset($this->serverParams['HTTPS']) && $this->serverParams['HTTPS'] === 'on' ? 'https' : 'http');
        
        return $scheme . '://' . 
               $this->serverParams['HTTP_HOST'] . 
               $this->serverParams['REQUEST_URI'];
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isPut(): bool
    {
        return $this->method() === 'PUT';
    }

    public function isDelete(): bool
    {
        return $this->method() === 'DELETE';
    }

    public function isAjax(): bool
    {
        return isset($this->serverParams['HTTP_X_REQUESTED_WITH']) && 
               $this->serverParams['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function all(): array
    {
        return array_merge($this->queryParams, $this->bodyParams);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function query(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->queryParams;
        }
        
        return $this->queryParams[$key] ?? $default;
    }

    public function post(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->bodyParams;
        }
        
        return $this->bodyParams[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->all()[$key]);
    }

    public function only(array $keys): array
    {
        $results = [];
        $input = $this->all();
        
        foreach ($keys as $key) {
            if (isset($input[$key])) {
                $results[$key] = $input[$key];
            }
        }
        
        return $results;
    }

    public function except(array $keys): array
    {
        $results = $this->all();
        
        foreach ($keys as $key) {
            unset($results[$key]);
        }
        
        return $results;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function ip(): string
    {
        if (!empty($this->serverParams['HTTP_CLIENT_IP'])) {
            return $this->serverParams['HTTP_CLIENT_IP'];
        }
        
        if (!empty($this->serverParams['HTTP_X_FORWARDED_FOR'])) {
            return $this->serverParams['HTTP_X_FORWARDED_FOR'];
        }
        
        return $this->serverParams['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function userAgent(): string
    {
        return $this->serverParams['HTTP_USER_AGENT'] ?? '';
    }

    public function header(string $key): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $this->serverParams[$key] ?? null;
    }

    public function validateCsrf(): bool
    {
        $token = $this->input('_csrf_token');
        $sessionToken = $_SESSION['_csrf_token'] ?? null;
        
        return $token && $sessionToken && hash_equals($sessionToken, $token);
    }
}