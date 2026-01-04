<?php

$rootPath = dirname(__DIR__);

return [
    'root' => $rootPath,
    'src' => $rootPath . '/src',
    'public' => $rootPath . '/public',
    'views' => $rootPath . '/views',
    'storage' => $rootPath . '/storage',
    'logs' => $rootPath . '/storage/logs',
    'cache' => $rootPath . '/storage/cache',
    'uploads' => $rootPath . '/public/uploads',
    'database' => $rootPath . '/database',
    'migrations' => $rootPath . '/database/migrations',
    'seeds' => $rootPath . '/database/seeds',
];