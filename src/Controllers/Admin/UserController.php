<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Services\UserService;
use App\Services\RoleService;
use App\Validators\UserValidator;

class UserController
{
    private UserService $userService;
    private RoleService $roleService;
    private UserValidator $validator;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->roleService = new RoleService();
        $this->validator = new UserValidator();
    }

    public function index(Request $request): Response
    {
        return Response::make()->view('admin.users.index');
    }

    public function table(Request $request): Response
    {
        $search = $request->query('search');
        $page = (int)$request->query('page', 1);
        
        $result = $search 
            ? $this->userService->search($search, $page)
            : $this->userService->getAll($page);
        
        // Load roles
        foreach ($result['data'] as $user) {
            $userWithRoles = $this->userService->getById($user->id);
            $user->roles = $userWithRoles->roles ?? [];
        }
        
        return Response::make()->view('admin.users._table', [
            'users' => $result['data'],
            'search' => $search
        ], false);
    }

    public function create(Request $request): Response
    {
        return Response::make()->view('admin.users.create', [
            'roles' => $this->roleService->getAll()
        ]);
    }

    public function store(Request $request): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        if (!$this->validator->validateCreate($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $user = $this->userService->create($data);

        if (!$user) {
            return Response::make()
                ->with('error', 'Email already registered')
                ->withInput()
                ->back();
        }

        return Response::make()
            ->with('success', 'User created successfully')
            ->redirect(url('admin/users'));
    }

    public function edit(Request $request, string $id): Response
    {
        $user = $this->userService->getById($id);

        if (!$user) {
            return Response::make()
                ->setStatusCode(404)
                ->setContent('User not found');
        }

        return Response::make()->view('admin.users.edit', [
            'user' => $user,
            'roles' => $this->roleService->getAll()
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        if (!$this->validator->validateUpdate($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $user = $this->userService->update($id, $data);

        if (!$user) {
            return Response::make()
                ->with('error', 'Email already registered or user not found')
                ->withInput()
                ->back();
        }

        return Response::make()
            ->with('success', 'User updated successfully')
            ->redirect(url('admin/users'));
    }

    public function confirmDelete(Request $request, string $id): Response
    {
        $user = $this->userService->getById($id);
        
        if (!$user) {
            return Response::make()
                ->setStatusCode(404)
                ->setContent('User tidak ditemukan');
        }
        
        return Response::make()->view('admin.users._delete_modal', ['user' => $user], false);
    }

    public function destroy(Request $request, string $id): Response
    {
        $isAjax = $request->header('Accept') === 'application/json' || 
                  $request->header('X-Requested-With') === 'XMLHttpRequest';
        $isHtmx = $request->header('HX-Request') !== null;
        
        $deleted = $this->userService->delete($id);
        
        // HTMX response
        if ($isHtmx) {
            if (!$deleted) {
                return Response::make()
                    ->setStatusCode(400)
                    ->setContent($this->htmxScript('Gagal menghapus user', 'danger'));
            }
            
            header('HX-Trigger: userDeleted');
            return Response::make()->setContent($this->htmxScript('User berhasil dihapus', 'success'));
        }
        
        // AJAX response
        if ($isAjax) {
            return Response::make()->json([
                'success' => $deleted,
                'message' => $deleted ? 'User berhasil dihapus' : 'Gagal menghapus user'
            ], $deleted ? 200 : 500);
        }
        
        // Form redirect
        return Response::make()
            ->with($deleted ? 'success' : 'error', $deleted ? 'User deleted successfully' : 'Failed to delete user')
            ->redirect($deleted ? url('admin/users') : null)
            ->back();
    }
    
    private function htmxScript(string $message, string $type): string
    {
        return '<script>
            document.getElementById("delete-modal").innerHTML = "";
            showToast("' . addslashes($message) . '", "' . $type . '");
        </script>';
    }
}