<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\User;

class UserRepository
{
    public function findAll(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new User((array)$row), $result);
    }

    public function findById(string $id): ?User
    {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new User((array)$result) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        
        $result = Database::fetchOne($sql, ['email' => $email]);

        return $result ? new User((array)$result) : null;
    }

    public function findWithRoles(string $id): ?User
    {
        $sql = "
            SELECT u.*, 
                   json_agg(
                       json_build_object(
                           'id', r.id,
                           'name', r.name,
                           'description', r.description
                       )
                   ) FILTER (WHERE r.id IS NOT NULL) as roles
            FROM users u
            LEFT JOIN role_user ru ON u.id = ru.user_id
            LEFT JOIN roles r ON ru.role_id = r.id
            WHERE u.id = :id
            GROUP BY u.id
            LIMIT 1
        ";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        if (!$result) {
            return null;
        }

        $userData = (array)$result;
        $rolesJson = $userData['roles'] ?? '[]';
        unset($userData['roles']);

        $user = new User($userData);
        
        $user->roles = json_decode($rolesJson, true) ?? [];

        return $user;
    }

    public function create(User $user): bool
    {
        $sql = "
            INSERT INTO users (id, name, email, password, created_at, updated_at)
            VALUES (:id, :name, :email, :password, :created_at, :updated_at)
        ";

        return Database::execute($sql, [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]);
    }

    public function update(User $user): bool
    {
        $sql = "
            UPDATE users 
            SET name = :name, 
                email = :email, 
                updated_at = :updated_at
            WHERE id = :id
        ";

        return Database::execute($sql, [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'updated_at' => $user->updated_at
        ]);
    }

    public function updatePassword(string $id, string $password): bool
    {
        $sql = "UPDATE users SET password = :password, updated_at = :updated_at WHERE id = :id";

        return Database::execute($sql, [
            'id' => $id,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";

        return Database::execute($sql, ['id' => $id]);
    }

    public function attachRole(string $userId, string $roleId): bool
    {
        $sql = "
            INSERT INTO role_user (user_id, role_id)
            VALUES (:user_id, :role_id)
            ON CONFLICT (user_id, role_id) DO NOTHING
        ";

        return Database::execute($sql, [
            'user_id' => $userId,
            'role_id' => $roleId
        ]);
    }

    public function detachRole(string $userId, string $roleId): bool
    {
        $sql = "DELETE FROM role_user WHERE user_id = :user_id AND role_id = :role_id";

        return Database::execute($sql, [
            'user_id' => $userId,
            'role_id' => $roleId
        ]);
    }

    public function syncRoles(string $userId, array $roleIds): bool
    {
        Database::beginTransaction();

        try {
            // Delete existing roles
            $deleteSql = "DELETE FROM role_user WHERE user_id = :user_id";
            Database::execute($deleteSql, ['user_id' => $userId]);

            // Insert new roles
            if (!empty($roleIds)) {
                $insertSql = "INSERT INTO role_user (user_id, role_id) VALUES (:user_id, :role_id)";
                
                foreach ($roleIds as $roleId) {
                    Database::execute($insertSql, [
                        'user_id' => $userId,
                        'role_id' => $roleId
                    ]);
                }
            }

            Database::commit();
            return true;
        } catch (\Exception $e) {
            Database::rollback();
            return false;
        }
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = Database::fetchOne($sql);

        return (int)$result->total;
    }

    public function search(string $keyword, int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT * FROM users 
            WHERE name ILIKE :keyword OR email ILIKE :keyword
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $result = Database::query($sql, [
            'keyword' => "%{$keyword}%",
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new User((array)$row), $result);
    }
}