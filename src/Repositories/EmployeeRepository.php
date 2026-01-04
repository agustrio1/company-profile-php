<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Employee;

class EmployeeRepository
{
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT e.*, t.name as team_name
            FROM employees e
            LEFT JOIN teams t ON e.team_id = t.id
            ORDER BY e.sort_order ASC, e.name ASC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Employee((array)$row), $result);
    }

    public function findByTeamId(string $teamId, int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT e.*, t.name as team_name
            FROM employees e
            LEFT JOIN teams t ON e.team_id = t.id
            WHERE e.team_id = :team_id 
            ORDER BY e.sort_order ASC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = Database::query($sql, [
            'team_id' => $teamId,
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Employee((array)$row), $result);
    }

    public function findById(string $id): ?Employee
    {
        $sql = "
            SELECT e.*, t.name as team_name
            FROM employees e
            LEFT JOIN teams t ON e.team_id = t.id
            WHERE e.id = :id 
            LIMIT 1
        ";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new Employee((array)$result) : null;
    }

    public function create(Employee $employee): bool
    {
        $sql = "
            INSERT INTO employees (id, team_id, name, position, photo, bio, sort_order)
            VALUES (:id, :team_id, :name, :position, :photo, :bio, :sort_order)
        ";

        return Database::execute($sql, [
            'id' => $employee->id,
            'team_id' => $employee->team_id,
            'name' => $employee->name,
            'position' => $employee->position,
            'photo' => $employee->photo,
            'bio' => $employee->bio,
            'sort_order' => $employee->sort_order
        ]);
    }

    public function update(Employee $employee): bool
    {
        $sql = "
            UPDATE employees 
            SET team_id = :team_id, 
                name = :name, 
                position = :position, 
                photo = :photo, 
                bio = :bio, 
                sort_order = :sort_order
            WHERE id = :id
        ";

        return Database::execute($sql, [
            'id' => $employee->id,
            'team_id' => $employee->team_id,
            'name' => $employee->name,
            'position' => $employee->position,
            'photo' => $employee->photo,
            'bio' => $employee->bio,
            'sort_order' => $employee->sort_order
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM employees WHERE id = :id";

        return Database::execute($sql, ['id' => $id]);
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM employees";
        $result = Database::fetchOne($sql);

        return (int)$result->total;
    }

    public function countByTeamId(string $teamId): int
    {
        $sql = "SELECT COUNT(*) as total FROM employees WHERE team_id = :team_id";
        $result = Database::fetchOne($sql, ['team_id' => $teamId]);

        return (int)$result->total;
    }

    public function search(string $keyword, int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT e.*, t.name as team_name
            FROM employees e
            LEFT JOIN teams t ON e.team_id = t.id
            WHERE e.name ILIKE :keyword OR e.position ILIKE :keyword OR e.bio ILIKE :keyword
            ORDER BY e.sort_order ASC, e.name ASC
            LIMIT :limit OFFSET :offset
        ";

        $result = Database::query($sql, [
            'keyword' => "%{$keyword}%",
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Employee((array)$row), $result);
    }

    public function getNextSortOrder(string $teamId): int
    {
        $sql = "SELECT COALESCE(MAX(sort_order), -1) + 1 as next_order FROM employees WHERE team_id = :team_id";
        $result = Database::fetchOne($sql, ['team_id' => $teamId]);

        return (int)$result->next_order;
    }
}