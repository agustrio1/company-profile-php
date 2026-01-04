<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Services\TeamService;
use App\Services\EmployeeService;
use App\Validators\TeamValidator;

class TeamController
{
    private TeamService $teamService;
    private EmployeeService $employeeService;
    private TeamValidator $validator;

    public function __construct()
    {
        $this->teamService = new TeamService();
        $this->employeeService = new EmployeeService();
        $this->validator = new TeamValidator();
    }

    // Team Methods
    public function index(Request $request): Response
    {
        return Response::make()->view('admin.teams.index');
    }

    public function table(Request $request): Response
    {
        $search = $request->query('search');
        $page = (int)$request->query('page', 1);
        
        $result = $search 
            ? $this->teamService->search($search, $page, 50)
            : $this->teamService->getAllWithEmployees($page, 50);
        
        return Response::make()->view('admin.teams._table', [
            'teams' => $result['data'],
            'search' => $search
        ], false);
    }

    public function create(Request $request): Response
    {
        return Response::make()->view('admin.teams.create');
    }

    public function store(Request $request): Response
    {
        return $this->handleStore(
            $request->except(['_csrf_token', '_method']),
            fn($data) => $this->teamService->create($data),
            'validateTeam',
            'admin/teams',
            'Team created successfully',
            'Failed to create team'
        );
    }

    public function edit(Request $request, string $id): Response
    {
        $team = $this->teamService->getById($id);
        if (!$team) return $this->notFound('Team not found');

        return Response::make()->view('admin.teams.edit', ['team' => $team]);
    }

    public function update(Request $request, string $id): Response
    {
        return $this->handleStore(
            $request->except(['_csrf_token', '_method']),
            fn($data) => $this->teamService->update($id, $data),
            'validateTeam',
            'admin/teams',
            'Team updated successfully',
            'Failed to update team'
        );
    }

    public function confirmDelete(Request $request, string $id): Response
    {
        $team = $this->teamService->getById($id);
        if (!$team) return $this->notFound('Team tidak ditemukan');
        
        return Response::make()->view('admin.teams._delete_modal', ['team' => $team], false);
    }

    public function destroy(Request $request, string $id): Response
    {
        return $this->handleDelete(
            $request,
            fn() => $this->teamService->delete($id),
            'teamDeleted',
            'Team berhasil dihapus',
            'Gagal menghapus team'
        );
    }

    // Employee Methods
    public function createEmployee(Request $request, string $teamId): Response
    {
        $team = $this->teamService->getById($teamId);
        if (!$team) return $this->notFound('Team not found');

        return Response::make()->view('admin.teams.create-employee', [
            'team' => $team,
            'teams' => $this->teamService->getAll(1, 100)['data']
        ]);
    }

    public function storeEmployee(Request $request): Response
    {
        $data = $request->except(['_csrf_token', '_method']);
        $photo = $request->file('photo');

        if (!$this->validator->validateEmployee($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $employee = $this->employeeService->create($data, $photo);

        return $employee
            ? Response::make()->with('success', 'Employee created successfully')->redirect(url('admin/teams'))
            : Response::make()->with('error', 'Failed to create employee')->back();
    }

    public function editEmployee(Request $request, string $id): Response
    {
        $employee = $this->employeeService->getById($id);
        if (!$employee) return $this->notFound('Employee not found');

        return Response::make()->view('admin.teams.edit-employee', [
            'employee' => $employee,
            'teams' => $this->teamService->getAll(1, 100)['data']
        ]);
    }

    public function updateEmployee(Request $request, string $id): Response
    {
        $data = $request->except(['_csrf_token', '_method']);
        $photo = $request->file('photo');

        if (!$this->validator->validateEmployee($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $employee = $this->employeeService->update($id, $data, $photo);

        return $employee
            ? Response::make()->with('success', 'Employee updated successfully')->redirect(url('admin/teams'))
            : Response::make()->with('error', 'Failed to update employee')->back();
    }

    public function confirmDeleteEmployee(Request $request, string $id): Response
    {
        $employee = $this->employeeService->getById($id);
        if (!$employee) return $this->notFound('Employee tidak ditemukan');
        
        return Response::make()->view('admin.teams._delete_employee_modal', ['employee' => $employee], false);
    }

    public function destroyEmployee(Request $request, string $id): Response
    {
        return $this->handleDelete(
            $request,
            fn() => $this->employeeService->delete($id),
            'employeeDeleted',
            'Employee berhasil dihapus',
            'Gagal menghapus employee'
        );
    }

    // Helper Methods
    private function handleStore(array $data, callable $action, string $validatorMethod, string $redirectUrl, string $successMsg, string $errorMsg): Response
    {
        if (!$this->validator->$validatorMethod($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $result = $action($data);

        return $result
            ? Response::make()->with('success', $successMsg)->redirect(url($redirectUrl))
            : Response::make()->with('error', $errorMsg)->back();
    }

    private function handleDelete(Request $request, callable $deleteAction, string $triggerEvent, string $successMsg, string $errorMsg): Response
    {
        $isHtmx = $request->header('HX-Request') !== null;
        $deleted = $deleteAction();
        
        if ($isHtmx) {
            if (!$deleted) {
                return Response::make()
                    ->setStatusCode(400)
                    ->setContent($this->htmxScript($errorMsg, 'danger'));
            }
            
            header("HX-Trigger: $triggerEvent");
            return Response::make()->setContent($this->htmxScript($successMsg, 'success'));
        }
        
        return Response::make()
            ->with($deleted ? 'success' : 'error', $deleted ? $successMsg : $errorMsg)
            ->redirect($deleted ? url('admin/teams') : null)
            ->back();
    }

    private function notFound(string $message): Response
    {
        return Response::make()->setStatusCode(404)->view('errors.404');
    }
    
    private function htmxScript(string $message, string $type): string
    {
        return '<script>
            document.getElementById("delete-modal").innerHTML = "";
            showToast("' . addslashes($message) . '", "' . $type . '");
        </script>';
    }
}