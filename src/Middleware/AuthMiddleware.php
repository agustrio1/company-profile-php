<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

class AuthMiddleware
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function handle(Request $request): ?Response
    {
        $token = $_COOKIE['auth_token'] ?? null;

        if (!$token) {
            return $this->unauthorized($request);
        }

        $user = $this->authService->validateSession($token);

        if (!$user) {
            $this->clearAuthCookie();
            return $this->unauthorized($request);
        }

        if (!isset($_SESSION['auth_user_id'])) {
            $_SESSION['auth_user_id'] = $user->id;
        }

        return null;
    }

    private function unauthorized(Request $request): Response
    {
        if ($request->isAjax()) {
            return Response::make()
                ->json(['error' => 'Unauthorized'], 401);
        }

        $_SESSION['intended_url'] = $request->url();

        return Response::make()
            ->with('error', 'Please login to continue')
            ->redirect(url('/'));
    }

    private function clearAuthCookie(): void
    {
        setcookie('auth_token', '', [
          'expires' => time() - 3600,
          'path' => '/',
          'secure' => true,
          'httponly' => true,
          'samesite' => 'Strict'
      ]);
    }
}