<?php

namespace App\Services;

use App\Models\PasswordReset;
use App\Repositories\PasswordResetRepository;
use App\Repositories\UserRepository;

class PasswordResetService
{
    private PasswordResetRepository $resetRepo;
    private UserRepository $userRepo;
    private EmailService $emailService;

    public function __construct()
    {
        $this->resetRepo = new PasswordResetRepository();
        $this->userRepo = new UserRepository();
        $this->emailService = new EmailService();
    }

    public function requestReset(string $email): bool
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            // Don't reveal if email exists or not
            return true;
        }

        // Delete old reset tokens for this user
        $this->resetRepo->deleteByUserId($user->id);

        // Create new reset token
        $reset = new PasswordReset([
            'user_id' => $user->id,
            'token' => bin2hex(random_bytes(32)),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'created_at' => now()
        ]);

        $reset->setUlid();

        if ($this->resetRepo->create($reset)) {
            // Send reset email
            $resetUrl = url('reset-password?token=' . $reset->token);
            return $this->emailService->sendForgotPassword($user->email, $user->name, $resetUrl);
        }

        return false;
    }

    public function validateToken(string $token): bool
    {
        $reset = $this->resetRepo->findByToken($token);

        return $reset && !$reset->isExpired() && !$reset->isUsed();
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $reset = $this->resetRepo->findByToken($token);

        if (!$reset || $reset->isExpired() || $reset->isUsed()) {
            return false;
        }

        // Update password
        if ($this->userRepo->updatePassword($reset->user_id, $newPassword)) {
            // Mark token as used
            $this->resetRepo->markAsUsed($token);

            // Send success email
            $user = $this->userRepo->findById($reset->user_id);
            if ($user) {
                $this->emailService->sendPasswordResetSuccess($user->email, $user->name);
            }

            return true;
        }

        return false;
    }

    public function cleanupExpired(): bool
    {
        return $this->resetRepo->deleteExpired();
    }
}