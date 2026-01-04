<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Company Profile',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => $_ENV['APP_DEBUG'] ?? false,
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta',
    'locale' => $_ENV['APP_LOCALE'] ?? 'id',
    
    'session' => [
        'lifetime' => 120, // minutes
        'expire_on_close' => false,
        'cookie_name' => 'app_session',
        'cookie_path' => '/',
        'cookie_domain' => null,
        'cookie_secure' => $_ENV['APP_ENV'] === 'production',
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ],
    
    'upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
        'path' => 'uploads/',
    ],
    
    'pagination' => [
        'per_page' => 10,
    ],
];