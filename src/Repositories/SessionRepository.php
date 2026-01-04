<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Session;

class SessionRepository
{
    public function findByToken(string $token): ?Session
    {
        $sql = "SELECT * FROM sessions WHERE token = :token LIMIT 1";
        
        $result = Database::fetchOne($sql, ['token' => $token]);

        return $result ? new Session((array)$result) : null;
    }

    public function findByUserId(string $userId): array
    {
        $sql = "
            SELECT * FROM sessions 
            WHERE user_id = :user_id 
            ORDER BY last_activity DESC
        ";
        
        $result = Database::query($sql, ['user_id' => $userId])->fetchAll();

        return array_map(fn($row) => new Session((array)$row), $result);
    }

    public function create(Session $session): bool
    {
        $sql = "
            INSERT INTO sessions (id, user_id, token, ip_address, user_agent, last_activity, expires_at, created_at)
            VALUES (:id, :user_id, :token, :ip_address, :user_agent, :last_activity, :expires_at, :created_at)
        ";

        return Database::execute($sql, [
            'id' => $session->id,
            'user_id' => $session->user_id,
            'token' => $session->token,
            'ip_address' => $session->ip_address,
            'user_agent' => $session->user_agent,
            'last_activity' => $session->last_activity,
            'expires_at' => $session->expires_at,
            'created_at' => $session->created_at
        ]);
    }

    public function updateActivity(string $token): bool
    {
        $sql = "UPDATE sessions SET last_activity = :last_activity WHERE token = :token";

        return Database::execute($sql, [
            'token' => $token,
            'last_activity' => date('Y-m-d H:i:s')
        ]);
    }

    public function delete(string $token): bool
    {
        $sql = "DELETE FROM sessions WHERE token = :token";

        return Database::execute($sql, ['token' => $token]);
    }

    public function deleteExpired(): bool
    {
        $sql = "DELETE FROM sessions WHERE expires_at < :now";

        return Database::execute($sql, ['now' => date('Y-m-d H:i:s')]);
    }

    public function deleteByUserId(string $userId): bool
    {
        $sql = "DELETE FROM sessions WHERE user_id = :user_id";

        return Database::execute($sql, ['user_id' => $userId]);
    }
}