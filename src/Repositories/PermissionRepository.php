<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Permission;

class PermissionRepository
{
    public function findAll(): array
    {
        $sql = "SELECT * FROM permissions ORDER BY name ASC";
        
        $result = Database::query($sql)->fetchAll();

        return array_map(fn($row) => new Permission((array)$row), $result);
    }

    public function findById(string $id): ?Permission
    {
        $sql = "SELECT * FROM permissions WHERE id = :id LIMIT 1";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new Permission((array)$result) : null;
    }

    public function findByName(string $name): ?Permission
    {
        $sql = "SELECT * FROM permissions WHERE name = :name LIMIT 1";
        
        $result = Database::fetchOne($sql, ['name' => $name]);

        return $result ? new Permission((array)$result) : null;
    }

    public function create(Permission $permission): bool
    {
        $sql = "INSERT INTO permissions (id, name, description) VALUES (:id, :name, :description)";

        return Database::execute($sql, [
            'id' => $permission->id,
            'name' => $permission->name,
            'description' => $permission->description
        ]);
    }

    public function update(Permission $permission): bool
    {
        $sql = "UPDATE permissions SET name = :name, description = :description WHERE id = :id";

        return Database::execute($sql, [
            'id' => $permission->id,
            'name' => $permission->name,
            'description' => $permission->description
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM permissions WHERE id = :id";

        return Database::execute($sql, ['id' => $id]);
    }

    public function findByRoleId(string $roleId): array
    {
        $sql = "
            SELECT p.* 
            FROM permissions p
            INNER JOIN permission_role pr ON p.id = pr.permission_id
            WHERE pr.role_id = :role_id
            ORDER BY p.name ASC
        ";

        $result = Database::query($sql, ['role_id' => $roleId])->fetchAll();

        return array_map(fn($row) => new Permission((array)$row), $result);
    }

    public function findByUserId(string $userId): array
    {
        $sql = "
            SELECT DISTINCT p.* 
            FROM permissions p
            INNER JOIN permission_role pr ON p.id = pr.permission_id
            INNER JOIN role_user ru ON pr.role_id = ru.role_id
            WHERE ru.user_id = :user_id
            ORDER BY p.name ASC
        ";

        $result = Database::query($sql, ['user_id' => $userId])->fetchAll();

        return array_map(fn($row) => new Permission((array)$row), $result);
    }
}