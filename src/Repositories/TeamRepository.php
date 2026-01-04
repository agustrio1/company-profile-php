<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Team;
use App\Models\Employee;

class TeamRepository
{
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT * FROM teams 
            ORDER BY name ASC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Team((array)$row), $result);
    }

    public function findWithEmployees(int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT t.*, 
                   json_agg(
                       json_build_object(
                           'id', e.id,
                           'name', e.name,
                           'position', e.position,
                           'photo', e.photo,
                           'bio', e.bio,
                           'sort_order', e.sort_order
                       ) ORDER BY e.sort_order ASC
                   ) FILTER (WHERE e.id IS NOT NULL) as employees
            FROM teams t
            LEFT JOIN employees e ON t.id = e.team_id
            GROUP BY t.id
            ORDER BY t.name ASC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        $teams = [];
        foreach ($result as $row) {
            $team = new Team((array)$row);
            $team->employees = json_decode($row->employees ?? '[]', true) ?? [];
            $teams[] = $team;
        }

        return $teams;
    }

    public function findById(string $id): ?Team
    {
        $sql = "SELECT * FROM teams WHERE id = :id LIMIT 1";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new Team((array)$result) : null;
    }

    public function create(Team $team): bool
    {
        $sql = "INSERT INTO teams (id, name, description) VALUES (:id, :name, :description)";

        return Database::execute($sql, [
            'id' => $team->id,
            'name' => $team->name,
            'description' => $team->description
        ]);
    }

    public function update(Team $team): bool
    {
        $sql = "UPDATE teams SET name = :name, description = :description WHERE id = :id";

        return Database::execute($sql, [
            'id' => $team->id,
            'name' => $team->name,
            'description' => $team->description
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM teams WHERE id = :id";

        return Database::execute($sql, ['id' => $id]);
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM teams";
        $result = Database::fetchOne($sql);

        return (int)$result->total;
    }

    public function search(string $keyword, int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT * FROM teams
            WHERE name ILIKE :keyword OR description ILIKE :keyword
            ORDER BY name ASC
            LIMIT :limit OFFSET :offset
        ";

        $result = Database::query($sql, [
            'keyword' => "%{$keyword}%",
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Team((array)$row), $result);
    }
}