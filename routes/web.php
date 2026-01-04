<?php

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\ServiceController;
use App\Controllers\TeamController;
use App\Controllers\AuthController;
use App\Middleware\GuestMiddleware;

// Home & Public Pages
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/contact', [HomeController::class, 'contact']);

// Blog Routes
$router->get('/blog', [BlogController::class, 'index']);
$router->get('/blog/{slug}', [BlogController::class, 'show']);
$router->get('/blog/category/{slug}', [BlogController::class, 'category']);

// Service Routes
$router->get('/services', [ServiceController::class, 'index']);
$router->get('/services/{slug}', [ServiceController::class, 'show']);

// Team Routes
$router->get('/team', [TeamController::class, 'index']);

// Auth Routes (Guest Only)
$router->group(['middleware' => GuestMiddleware::class], function ($router) {
    // Login
    $router->get('/login', [AuthController::class, 'showLogin']);
    $router->post('/login', [AuthController::class, 'login']);
    
    // Register
  /*  $router->get('/register', [AuthController::class, 'showRegister']);
    $router->post('/register', [AuthController::class, 'register']);
    */
    
    // Forgot Password
    /*$router->get('/forgot-password', [AuthController::class, 'showForgotPassword']);
    $router->post('/forgot-password', [AuthController::class, 'forgotPassword']);
    
    // Reset Password
    $router->get('/reset-password', [AuthController::class, 'showResetPassword']);
    $router->post('/reset-password', [AuthController::class, 'resetPassword']);
    */
});

// Logout (Auth Required)
$router->post('/logout', [AuthController::class, 'logout']);