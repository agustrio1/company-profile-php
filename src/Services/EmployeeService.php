<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;

class EmployeeService
{
    private EmployeeRepository $employeeRepo;
    private UploadService $uploadService;

    public function __construct()
    {
        $this->employeeRepo = new EmployeeRepository();
        $this->uploadService = new UploadService();
    }

    public function getAll(int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $employees = $this->employeeRepo->findAll($perPage, $offset);
        $total = $this->employeeRepo->count();

        return [
            'data' => $employees,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function getByTeamId(string $teamId, int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $employees = $this->employeeRepo->findByTeamId($teamId, $perPage, $offset);
        $total = $this->employeeRepo->countByTeamId($teamId);

        return [
            'data' => $employees,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function getById(string $id): ?Employee
    {
        return $this->employeeRepo->findById($id);
    }

    public function create(array $data, ?array $photo = null): ?Employee
    {
        $employee = new Employee([
            'team_id' => $data['team_id'] ?? null,
            'name' => $data['name'],
            'position' => $data['position'] ?? null,
            'bio' => $data['bio'] ?? null,
            'sort_order' => $data['sort_order'] ?? $this->employeeRepo->getNextSortOrder($data['team_id'] ?? '')
        ]);

        $employee->setUlid();

        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            $employee->photo = $this->uploadService->uploadImage($photo, 'team');
        }

        if ($this->employeeRepo->create($employee)) {
            return $employee;
        }

        return null;
    }

    public function update(string $id, array $data, ?array $photo = null): ?Employee
    {
        $employee = $this->employeeRepo->findById($id);

        if (!$employee) {
            return null;
        }

        $employee->team_id = $data['team_id'] ?? $employee->team_id;
        $employee->name = $data['name'];
        $employee->position = $data['position'] ?? null;
        $employee->bio = $data['bio'] ?? null;
        $employee->sort_order = $data['sort_order'] ?? $employee->sort_order;

        if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
            if ($employee->photo) {
                $this->uploadService->deleteFile($employee->photo);
            }
            
            $employee->photo = $this->uploadService->uploadImage($photo, 'team');
        }

        if ($this->employeeRepo->update($employee)) {
            return $employee;
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $employee = $this->employeeRepo->findById($id);

        if (!$employee) {
            return false;
        }

        if ($employee->photo) {
            $this->uploadService->deleteFile($employee->photo);
        }

        return $this->employeeRepo->delete($id);
    }

    public function search(string $keyword, int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $employees = $this->employeeRepo->search($keyword, $perPage, $offset);

        return [
            'data' => $employees,
            'keyword' => $keyword
        ];
    }
}