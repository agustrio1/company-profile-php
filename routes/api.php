<?php

use App\Controllers\Api\UploadImageController;
use App\Middleware\AuthMiddleware;

// Image Upload untuk WYSIWYG Editor (butuh auth)
$router->post('/api/upload-image', [UploadImageController::class, 'uploadImage'])
    ->middleware(AuthMiddleware::class);