<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\PasswordReset;

class PasswordResetRepository
{
    public function findByToken(string $token): ?PasswordReset
    {
        $sql = "
            SELECT * FROM password_resets 
            WHERE token = :token 
            AND expires_at > :now 
            AND used_at IS NULL
            LIMIT 1
        ";
        
        $result = Database::fetchOne($sql, [
            'token' => $token,
            'now' => date('Y-m-d H:i:s')
        ]);

        return $result ? new PasswordReset((array)$result) : null;
    }

    public function findByUserId(string $userId): array
    {
        $sql = "
            SELECT * FROM password_resets 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC
        ";
        
        $result = Database::query($sql, ['user_id' => $userId])->fetchAll();

        return array_map(fn($row) => new PasswordReset((array)$row), $result);
    }

    public function create(PasswordReset $passwordReset): bool
    {
        $sql = "
            INSERT INTO password_resets (id, user_id, token, expires_at, created_at)
            VALUES (:id, :user_id, :token, :expires_at, :created_at)
        ";

        return Database::execute($sql, [
            'id' => $passwordReset->id,
            'user_id' => $passwordReset->user_id,
            'token' => $passwordReset->token,
            'expires_at' => $passwordReset->expires_at,
            'created_at' => $passwordReset->created_at
        ]);
    }

    public function markAsUsed(string $token): bool
    {
        $sql = "UPDATE password_resets SET used_at = :used_at WHERE token = :token";

        return Database::execute($sql, [
            'token' => $token,
            'used_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function delete(string $token): bool
    {
        $sql = "DELETE FROM password_resets WHERE token = :token";

        return Database::execute($sql, ['token' => $token]);
    }

    public function deleteExpired(): bool
    {
        $sql = "DELETE FROM password_resets WHERE expires_at < :now";

        return Database::execute($sql, ['now' => date('Y-m-d H:i:s')]);
    }

    public function deleteByUserId(string $userId): bool
    {
        $sql = "DELETE FROM password_resets WHERE user_id = :user_id";

        return Database::execute($sql, ['user_id' => $userId]);
    }
}