<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Role;

class RoleRepository
{
    public function findAll(): array
    {
        $sql = "SELECT * FROM roles ORDER BY name ASC";
        
        $result = Database::query($sql)->fetchAll();

        return array_map(fn($row) => new Role((array)$row), $result);
    }

    // FIX: Method baru untuk load semua roles dengan permissions
    public function findAllWithPermissions(): array
    {
        $sql = "
            SELECT 
                r.*,
                COUNT(DISTINCT pr.permission_id) as permission_count
            FROM roles r
            LEFT JOIN permission_role pr ON r.id = pr.role_id
            GROUP BY r.id
            ORDER BY r.name ASC
        ";
        
        $result = Database::query($sql)->fetchAll();

        $roles = [];
        foreach ($result as $row) {
            $roleArray = (array)$row;
            $role = new Role($roleArray);
            
            // Load permissions untuk setiap role
            $permissions = $this->getPermissionsByRoleId($role->id);
            $role->permissions = $permissions;
            
            $roles[] = $role;
        }

        return $roles;
    }

    public function findById(string $id): ?Role
    {
        $sql = "SELECT * FROM roles WHERE id = :id LIMIT 1";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new Role((array)$result) : null;
    }

    public function findWithPermissions(string $id): ?Role
    {
        $role = $this->findById($id);

        if (!$role) {
            return null;
        }

        // Load permissions
        $role->permissions = $this->getPermissionsByRoleId($id);

        return $role;
    }

    public function findByName(string $name): ?Role
    {
        $sql = "SELECT * FROM roles WHERE name = :name LIMIT 1";
        
        $result = Database::fetchOne($sql, ['name' => $name]);

        return $result ? new Role((array)$result) : null;
    }

    public function create(Role $role): bool
    {
        $sql = "INSERT INTO roles (id, name, description) VALUES (:id, :name, :description)";

        return Database::execute($sql, [
            'id' => $role->id,
            'name' => $role->name,
            'description' => $role->description
        ]);
    }

    public function update(Role $role): bool
    {
        $sql = "UPDATE roles SET name = :name, description = :description WHERE id = :id";

        return Database::execute($sql, [
            'id' => $role->id,
            'name' => $role->name,
            'description' => $role->description
        ]);
    }

    public function delete(string $id): bool
    {
        // Delete role-permission relationships first
        $this->detachAllPermissions($id);
        
        // Delete role-user relationships
        $sql = "DELETE FROM role_user WHERE role_id = :id";
        Database::execute($sql, ['id' => $id]);
        
        // Delete role
        $sql = "DELETE FROM roles WHERE id = :id";
        return Database::execute($sql, ['id' => $id]);
    }

    // Helper method untuk get permissions by role ID
    private function getPermissionsByRoleId(string $roleId): array
    {
        $sql = "
            SELECT p.* 
            FROM permissions p
            INNER JOIN permission_role pr ON p.id = pr.permission_id
            WHERE pr.role_id = :role_id
            ORDER BY p.name ASC
        ";

        $result = Database::query($sql, ['role_id' => $roleId])->fetchAll();

        return array_map(function($row) {
            return (array)$row;
        }, $result);
    }

    public function attachPermission(string $roleId, string $permissionId): bool
    {
        // Check if already attached
        $sql = "SELECT COUNT(*) as count FROM permission_role WHERE role_id = :role_id AND permission_id = :permission_id";
        $result = Database::fetchOne($sql, [
            'role_id' => $roleId,
            'permission_id' => $permissionId
        ]);

        if ($result->count > 0) {
            return true; // Already attached
        }

        $sql = "INSERT INTO permission_role (role_id, permission_id) VALUES (:role_id, :permission_id)";

        return Database::execute($sql, [
            'role_id' => $roleId,
            'permission_id' => $permissionId
        ]);
    }

    public function detachPermission(string $roleId, string $permissionId): bool
    {
        $sql = "DELETE FROM permission_role WHERE role_id = :role_id AND permission_id = :permission_id";

        return Database::execute($sql, [
            'role_id' => $roleId,
            'permission_id' => $permissionId
        ]);
    }

    public function detachAllPermissions(string $roleId): bool
    {
        $sql = "DELETE FROM permission_role WHERE role_id = :role_id";

        return Database::execute($sql, ['role_id' => $roleId]);
    }

    public function syncPermissions(string $roleId, array $permissionIds): bool
    {
        // Remove all existing permissions
        $this->detachAllPermissions($roleId);

        // Attach new permissions
        if (empty($permissionIds)) {
            return true;
        }

        foreach ($permissionIds as $permissionId) {
            if (!$this->attachPermission($roleId, $permissionId)) {
                return false;
            }
        }

        return true;
    }

    public function getUsersByRoleId(string $roleId): array
    {
        $sql = "
            SELECT u.* 
            FROM users u
            INNER JOIN role_user ru ON u.id = ru.user_id
            WHERE ru.role_id = :role_id
            ORDER BY u.name ASC
        ";

        return Database::query($sql, ['role_id' => $roleId])->fetchAll();
    }
}