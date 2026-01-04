<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

class GuestMiddleware
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function handle(Request $request): ?Response
    {
        // Check if user has session token in cookie
        $token = $_COOKIE['auth_token'] ?? null;

        if (!$token) {
            // User is not logged in, allow access
            return null;
        }

        // Validate session
        $user = $this->authService->validateSession($token);

        if (!$user) {
            // Invalid session, allow access
            return null;
        }

        // User is logged in, redirect to dashboard
        $response = Response::make();
        $response->redirect(url('admin/dashboard'));
        
        return $response;
    }
}