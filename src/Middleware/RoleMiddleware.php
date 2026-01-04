<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\PermissionRepository;

class RoleMiddleware
{
    private PermissionRepository $permissionRepo;
    private array $requiredPermissions = [];

    public function __construct(array $permissions = [])
    {
        $this->permissionRepo = new PermissionRepository();
        $this->requiredPermissions = $permissions;
    }

    public function handle(Request $request): ?Response
    {
        // Get authenticated user from session
        $user = $_SESSION['auth_user'] ?? null;

        if (!$user) {
            return $this->forbidden($request);
        }

        // If no specific permissions required, just check if authenticated
        if (empty($this->requiredPermissions)) {
            return null;
        }

        // Get user permissions
        $userPermissions = $this->permissionRepo->findByUserId($user->id);
        $userPermissionNames = array_map(fn($p) => $p->name, $userPermissions);

        // Check if user has any of the required permissions
        $hasPermission = false;
        foreach ($this->requiredPermissions as $permission) {
            if (in_array($permission, $userPermissionNames)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            return $this->forbidden($request);
        }

        // Allow request to continue
        return null;
    }

    private function forbidden(Request $request): Response
    {
        // If AJAX request, return JSON
        if ($request->isAjax()) {
            return Response::make()
                ->json(['error' => 'Forbidden - Insufficient permissions'], 403);
        }

        // Redirect to 403 page or dashboard
        return Response::make()
        ->setStatusCode(403)
        ->view('errors.403');
    }

    // Static method to create middleware with permissions
    public static function permissions(array $permissions): self
    {
        return new self($permissions);
    }
}