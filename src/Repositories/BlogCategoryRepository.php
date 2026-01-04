<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\BlogCategory;

class BlogCategoryRepository
{
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT * FROM blog_categories ORDER BY name ASC LIMIT :limit OFFSET :offset";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new BlogCategory((array)$row), $result);
    }

    public function findById(string $id): ?BlogCategory
    {
        $sql = "SELECT * FROM blog_categories WHERE id = :id LIMIT 1";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new BlogCategory((array)$result) : null;
    }

    public function findBySlug(string $slug): ?BlogCategory
    {
        $sql = "SELECT * FROM blog_categories WHERE slug = :slug LIMIT 1";
        
        $result = Database::fetchOne($sql, ['slug' => $slug]);

        return $result ? new BlogCategory((array)$result) : null;
    }

    public function findWithBlogs(string $id): ?BlogCategory
    {
        $sql = "
            SELECT bc.*, 
                   json_agg(
                       json_build_object(
                           'id', b.id,
                           'title', b.title,
                           'slug', b.slug,
                           'status', b.status
                       )
                   ) FILTER (WHERE b.id IS NOT NULL) as blogs
            FROM blog_categories bc
            LEFT JOIN blogs b ON bc.id = b.category_id
            WHERE bc.id = :id
            GROUP BY bc.id
            LIMIT 1
        ";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        if (!$result) {
            return null;
        }

        $categoryData = (array)$result;
        $blogsJson = $categoryData['blogs'] ?? '[]';
        unset($categoryData['blogs']);

        $category = new BlogCategory($categoryData);
        $category->blogs = json_decode($blogsJson, true) ?? [];

        return $category;
    }

    public function create(BlogCategory $category): bool
    {
        $sql = "
            INSERT INTO blog_categories (id, name, slug, description)
            VALUES (:id, :name, :slug, :description)
        ";

        return Database::execute($sql, [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description
        ]);
    }

    public function update(BlogCategory $category): bool
    {
        $sql = "
            UPDATE blog_categories 
            SET name = :name, 
                slug = :slug, 
                description = :description
            WHERE id = :id
        ";

        return Database::execute($sql, [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM blog_categories WHERE id = :id";

        return Database::execute($sql, ['id' => $id]);
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM blog_categories";
        $result = Database::fetchOne($sql);

        return (int)$result->total;
    }

    public function search(string $keyword, int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT * FROM blog_categories 
            WHERE name ILIKE :keyword OR description ILIKE :keyword
            ORDER BY name ASC
            LIMIT :limit OFFSET :offset
        ";

        $result = Database::query($sql, [
            'keyword' => "%{$keyword}%",
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new BlogCategory((array)$row), $result);
    }

    public function getBlogCount(string $categoryId): int
    {
        $sql = "SELECT COUNT(*) as total FROM blogs WHERE category_id = :category_id";
        $result = Database::fetchOne($sql, ['category_id' => $categoryId]);

        return (int)$result->total;
    }
}