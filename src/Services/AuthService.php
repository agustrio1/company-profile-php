<?php

namespace App\Services;

use App\Models\User;
use App\Models\Session;
use App\Repositories\UserRepository;
use App\Repositories\SessionRepository;
use App\Core\Request;

class AuthService
{
    private UserRepository $userRepo;
    private SessionRepository $sessionRepo;
    private EmailService $emailService;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->sessionRepo = new SessionRepository();
        $this->emailService = new EmailService();
    }

    public function register(array $data): ?User
    {
        // Check if email exists
        if ($this->userRepo->findByEmail($data['email'])) {
            return null;
        }

        $user = new User([
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        $user->setUlid();
        $user->setPassword($data['password']);
        $user->touchTimestamps();

        if ($this->userRepo->create($user)) {
            // Send welcome email
            $this->emailService->sendWelcome($user->email, $user->name);
            
            return $user;
        }

        return null;
    }

    public function login(string $email, string $password, Request $request): ?array
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user || !$user->verifyPassword($password)) {
            return null;
        }

        // Create session
        $session = $this->createSession($user->id, $request);

        return [
            'user' => $user,
            'session' => $session
        ];
    }

    public function logout(string $token): bool
    {
        return $this->sessionRepo->delete($token);
    }

    public function validateSession(string $token): ?User
    {
        $session = $this->sessionRepo->findByToken($token);

        if (!$session || $session->isExpired()) {
            return null;
        }

        // Update last activity
        $this->sessionRepo->updateActivity($token);

        return $this->userRepo->findWithRoles($session->user_id);
    }

    private function createSession(string $userId, Request $request): Session
    {
        $session = new Session([
            'user_id' => $userId,
            'token' => bin2hex(random_bytes(32)),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'last_activity' => now(),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+' . config('app.session.lifetime') . ' minutes')),
            'created_at' => now()
        ]);

        $session->setUlid();
        $this->sessionRepo->create($session);

        return $session;
    }

    public function cleanupExpiredSessions(): bool
    {
        return $this->sessionRepo->deleteExpired();
    }
}