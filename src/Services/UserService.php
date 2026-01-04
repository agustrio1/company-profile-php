<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function getAll(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $users = $this->userRepo->findAll($perPage, $offset);
        $total = $this->userRepo->count();

        return [
            'data' => $users,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function getById(string $id): ?User
    {
        return $this->userRepo->findWithRoles($id);
    }

    public function create(array $data): ?User
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
            // Attach roles if provided
            if (!empty($data['roles'])) {
                $this->userRepo->syncRoles($user->id, $data['roles']);
            }

            return $user;
        }

        return null;
    }

    public function update(string $id, array $data): ?User
    {
        $user = $this->userRepo->findById($id);

        if (!$user) {
            return null;
        }

        // Check email uniqueness if changed
        if ($data['email'] !== $user->email) {
            $existing = $this->userRepo->findByEmail($data['email']);
            if ($existing && $existing->id !== $id) {
                return null;
            }
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->setUpdatedAt();

        if ($this->userRepo->update($user)) {
            // Update roles if provided
            if (isset($data['roles'])) {
                $this->userRepo->syncRoles($id, $data['roles']);
            }

            return $this->userRepo->findWithRoles($id);
        }

        return null;
    }

    public function updatePassword(string $id, string $newPassword): bool
    {
        return $this->userRepo->updatePassword($id, $newPassword);
    }

    public function delete(string $id): bool
    {
        return $this->userRepo->delete($id);
    }

    public function search(string $keyword, int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $users = $this->userRepo->search($keyword, $perPage, $offset);

        return [
            'data' => $users,
            'keyword' => $keyword
        ];
    }

    public function attachRole(string $userId, string $roleId): bool
    {
        return $this->userRepo->attachRole($userId, $roleId);
    }

    public function detachRole(string $userId, string $roleId): bool
    {
        return $this->userRepo->detachRole($userId, $roleId);
    }

    public function syncRoles(string $userId, array $roleIds): bool
    {
        return $this->userRepo->syncRoles($userId, $roleIds);
    }
}