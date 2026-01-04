<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Services\RoleService;
use App\Repositories\PermissionRepository;
use App\Validators\RoleValidator;

class RoleController
{
    private RoleService $roleService;
    private PermissionRepository $permissionRepo;
    private RoleValidator $validator;

    public function __construct()
    {
        $this->roleService = new RoleService();
        $this->permissionRepo = new PermissionRepository();
        $this->validator = new RoleValidator();
    }

    public function index(Request $request): Response
    {
        $roles = $this->roleService->getAll();
        
        // Debug: cek data roles
        error_log("Roles data: " . json_encode($roles));
        
        $data = [
            'roles' => $roles
        ];

        return Response::make()->view('admin.roles.index', $data);
    }

    public function create(Request $request): Response
    {
        $data = [
            'permissions' => $this->permissionRepo->findAll()
        ];

        return Response::make()->view('admin.roles.create', $data);
    }

    public function store(Request $request): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        if (!$this->validator->validateRole($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $role = $this->roleService->create($data);

        if (!$role) {
            return Response::make()
                ->with('error', 'Role name already exists')
                ->withInput()
                ->back();
        }

        return Response::make()
            ->with('success', 'Role created successfully')
            ->redirect(url('admin/roles'));
    }

    public function edit(Request $request, string $id): Response
    {
        $role = $this->roleService->getById($id);

        if (!$role) {
            return Response::make()
                ->setStatusCode(404)
                ->setContent('Role not found');
        }

        $data = [
            'role' => $role,
            'permissions' => $this->permissionRepo->findAll()
        ];

        return Response::make()->view('admin.roles.edit', $data);
    }

    public function update(Request $request, string $id): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        if (!$this->validator->validateRole($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $role = $this->roleService->update($id, $data);

        if (!$role) {
            return Response::make()
                ->with('error', 'Role name already exists or role not found')
                ->withInput()
                ->back();
        }

        return Response::make()
            ->with('success', 'Role updated successfully')
            ->redirect(url('admin/roles'));
    }

    public function destroy(Request $request, string $id): Response
    {
      
        // Cek apakah ini AJAX request
        $isAjax = $request->header('Accept') === 'application/json' || 
                  $request->header('X-Requested-With') === 'XMLHttpRequest';
        
        // Cek role sistem
        $role = $this->roleService->getById($id);
        if ($role && in_array($role->name, ['admin', 'super_admin'])) {
            if ($isAjax) {
                return Response::make()
                    ->json([
                        'success' => false,
                        'message' => 'Role sistem tidak dapat dihapus'
                    ], 403);
            }
            
            return Response::make()
                ->with('error', 'Role sistem tidak dapat dihapus')
                ->back();
        }
        
        $deleted = $this->roleService->delete($id);
        
        if ($isAjax) {
            if ($deleted) {
                return Response::make()
                    ->json([
                        'success' => true,
                        'message' => 'Role berhasil dihapus'
                    ]);
            }
            
            return Response::make()
                ->json([
                    'success' => false,
                    'message' => 'Gagal menghapus role'
                ], 500);
        }
        
        // Non-AJAX response
        if (!$deleted) {
            return Response::make()
                ->with('error', 'Failed to delete role')
                ->back();
        }

        return Response::make()
            ->with('success', 'Role deleted successfully')
            ->redirect(url('admin/roles'));
    }
}