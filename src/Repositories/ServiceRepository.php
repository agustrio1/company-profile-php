<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Service;

class ServiceRepository
{
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT * FROM services 
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Service((array)$row), $result);
    }

    public function findFeatured(int $limit = 10, int $offset = 0): array
    {
        $sql = "
            SELECT * FROM services 
            WHERE is_featured = true 
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Service((array)$row), $result);
    }

    public function findById(string $id): ?Service
    {
        $sql = "SELECT * FROM services WHERE id = :id LIMIT 1";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new Service((array)$result) : null;
    }

    public function findBySlug(string $slug): ?Service
    {
        $sql = "SELECT * FROM services WHERE slug = :slug LIMIT 1";
        
        $result = Database::fetchOne($sql, ['slug' => $slug]);

        return $result ? new Service((array)$result) : null;
    }

    public function create(Service $service): bool
    {
        $sql = "
            INSERT INTO services (id, title, slug, description, icon, image, is_featured, created_at, updated_at)
            VALUES (:id, :title, :slug, :description, :icon, :image, :is_featured, :created_at, :updated_at)
        ";

        return Database::execute($sql, [
            'id' => $service->id,
            'title' => $service->title,
            'slug' => $service->slug,
            'description' => $service->description,
            'icon' => $service->icon,
            'image' => $service->image,
            'is_featured' => $service->is_featured ? 'true' : 'false',
            'created_at' => $service->created_at,
            'updated_at' => $service->updated_at
        ]);
    }

    public function update(Service $service): bool
    {
        $sql = "
            UPDATE services 
            SET title = :title, 
                slug = :slug, 
                description = :description, 
                icon = :icon, 
                image = :image,
                is_featured = :is_featured,
                updated_at = :updated_at
            WHERE id = :id
        ";

        return Database::execute($sql, [
            'id' => $service->id,
            'title' => $service->title,
            'slug' => $service->slug,
            'description' => $service->description,
            'icon' => $service->icon,
            'image' => $service->image,
            'is_featured' => $service->is_featured ? 'true' : 'false',
            'updated_at' => $service->updated_at
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM services WHERE id = :id";

        return Database::execute($sql, ['id' => $id]);
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM services";
        $result = Database::fetchOne($sql);

        return (int)$result->total;
    }

    public function countFeatured(): int
    {
        $sql = "SELECT COUNT(*) as total FROM services WHERE is_featured = true";
        $result = Database::fetchOne($sql);

        return (int)$result->total;
    }

    public function search(string $keyword, int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT * FROM services
            WHERE title ILIKE :keyword OR description ILIKE :keyword
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $result = Database::query($sql, [
            'keyword' => "%{$keyword}%",
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Service((array)$row), $result);
    }
}