<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\RoleRepository;

class RoleService
{
    private RoleRepository $roleRepo;

    public function __construct()
    {
        $this->roleRepo = new RoleRepository();
    }

    // FIX: Load permissions untuk semua roles
    public function getAll(): array
    {
        return $this->roleRepo->findAllWithPermissions();
    }

    public function getById(string $id): ?Role
    {
        return $this->roleRepo->findWithPermissions($id);
    }

    public function getByName(string $name): ?Role
    {
        return $this->roleRepo->findByName($name);
    }

    public function create(array $data): ?Role
    {
        // Check if role name exists
        if ($this->roleRepo->findByName($data['name'])) {
            return null;
        }

        $role = new Role([
            'name' => $data['name'],
            'description' => $data['description'] ?? null
        ]);

        $role->setUlid();

        if ($this->roleRepo->create($role)) {
            // Attach permissions if provided
            if (!empty($data['permissions'])) {
                $this->roleRepo->syncPermissions($role->id, $data['permissions']);
            }

            return $role;
        }

        return null;
    }

    public function update(string $id, array $data): ?Role
    {
        $role = $this->roleRepo->findById($id);

        if (!$role) {
            return null;
        }

        // Check name uniqueness if changed
        if ($data['name'] !== $role->name) {
            $existing = $this->roleRepo->findByName($data['name']);
            if ($existing && $existing->id !== $id) {
                return null;
            }
        }

        $role->name = $data['name'];
        $role->description = $data['description'] ?? null;

        if ($this->roleRepo->update($role)) {
            // Update permissions if provided
            if (isset($data['permissions'])) {
                $this->roleRepo->syncPermissions($id, $data['permissions']);
            }

            return $this->roleRepo->findWithPermissions($id);
        }

        return null;
    }

    public function delete(string $id): bool
    {
        return $this->roleRepo->delete($id);
    }

    public function attachPermission(string $roleId, string $permissionId): bool
    {
        return $this->roleRepo->attachPermission($roleId, $permissionId);
    }

    public function detachPermission(string $roleId, string $permissionId): bool
    {
        return $this->roleRepo->detachPermission($roleId, $permissionId);
    }

    public function syncPermissions(string $roleId, array $permissionIds): bool
    {
        return $this->roleRepo->syncPermissions($roleId, $permissionIds);
    }
}