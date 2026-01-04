<?php

namespace App\Services;

use App\Models\Team;
use App\Repositories\TeamRepository;
use App\Repositories\EmployeeRepository;

class TeamService
{
    private TeamRepository $teamRepo;
    private EmployeeRepository $employeeRepo;
    private UploadService $uploadService;

    public function __construct()
    {
        $this->teamRepo = new TeamRepository();
        $this->employeeRepo = new EmployeeRepository();
        $this->uploadService = new UploadService();
    }

    public function getAll(int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $teams = $this->teamRepo->findAll($perPage, $offset);
        $total = $this->teamRepo->count();

        return [
            'data' => $teams,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function getAllWithEmployees(int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $teams = $this->teamRepo->findWithEmployees($perPage, $offset);
        $total = $this->teamRepo->count();

        return [
            'data' => $teams,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function getById(string $id): ?Team
    {
        $team = $this->teamRepo->findById($id);

        if ($team) {
            $team->employees = $this->employeeRepo->findByTeamId($id);
        }

        return $team;
    }

    public function create(array $data): ?Team
    {
        $team = new Team([
            'name' => $data['name'],
            'description' => $data['description'] ?? null
        ]);

        $team->setUlid();

        if ($this->teamRepo->create($team)) {
            return $team;
        }

        return null;
    }

    public function update(string $id, array $data): ?Team
    {
        $team = $this->teamRepo->findById($id);

        if (!$team) {
            return null;
        }

        $team->name = $data['name'];
        $team->description = $data['description'] ?? null;

        if ($this->teamRepo->update($team)) {
            return $team;
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $employees = $this->employeeRepo->findByTeamId($id);

        foreach ($employees as $employee) {
            if ($employee->photo) {
                $this->uploadService->deleteFile($employee->photo);
            }
        }

        return $this->teamRepo->delete($id);
    }

    public function search(string $keyword, int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $teams = $this->teamRepo->search($keyword, $perPage, $offset);

        return [
            'data' => $teams,
            'keyword' => $keyword
        ];
    }
}