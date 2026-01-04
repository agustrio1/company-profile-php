<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Blog;

class BlogRepository
{
    public function findAll(int $limit = 10, int $offset = 0): array
    {
        $sql = "
            SELECT b.*, 
                   bc.name as category_name, bc.slug as category_slug,
                   u.name as author_name
            FROM blogs b
            LEFT JOIN blog_categories bc ON b.category_id = bc.id
            LEFT JOIN users u ON b.author_id = u.id
            ORDER BY b.created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Blog((array)$row), $result);
    }

    public function findPublished(int $limit = 10, int $offset = 0): array
    {
        $sql = "
            SELECT b.*, 
                   bc.name as category_name, bc.slug as category_slug,
                   u.name as author_name
            FROM blogs b
            LEFT JOIN blog_categories bc ON b.category_id = bc.id
            LEFT JOIN users u ON b.author_id = u.id
            WHERE b.status = 'published'
            ORDER BY b.published_at DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Blog((array)$row), $result);
    }

    public function findById(string $id): ?Blog
    {
        $sql = "
            SELECT b.*, 
                   bc.name as category_name, bc.slug as category_slug,
                   u.name as author_name
            FROM blogs b
            LEFT JOIN blog_categories bc ON b.category_id = bc.id
            LEFT JOIN users u ON b.author_id = u.id
            WHERE b.id = :id
            LIMIT 1
        ";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new Blog((array)$result) : null;
    }

    public function findBySlug(string $slug): ?Blog
    {
        $sql = "
            SELECT b.*, 
                   bc.name as category_name, bc.slug as category_slug,
                   u.name as author_name
            FROM blogs b
            LEFT JOIN blog_categories bc ON b.category_id = bc.id
            LEFT JOIN users u ON b.author_id = u.id
            WHERE b.slug = :slug
            LIMIT 1
        ";
        
        $result = Database::fetchOne($sql, ['slug' => $slug]);

        return $result ? new Blog((array)$result) : null;
    }

    public function findByCategoryId(string $categoryId, int $limit = 10, int $offset = 0): array
    {
        $sql = "
            SELECT b.*, 
                   bc.name as category_name, bc.slug as category_slug,
                   u.name as author_name
            FROM blogs b
            LEFT JOIN blog_categories bc ON b.category_id = bc.id
            LEFT JOIN users u ON b.author_id = u.id
            WHERE b.category_id = :category_id AND b.status = 'published'
            ORDER BY b.published_at DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $result = Database::query($sql, [
            'category_id' => $categoryId,
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Blog((array)$row), $result);
    }

    public function create(Blog $blog): bool
    {
        $sql = "
            INSERT INTO blogs (id, category_id, title, slug, content, thumbnail, author_id, published_at, status, created_at, updated_at)
            VALUES (:id, :category_id, :title, :slug, :content, :thumbnail, :author_id, :published_at, :status, :created_at, :updated_at)
        ";

        return Database::execute($sql, [
            'id' => $blog->id,
            'category_id' => $blog->category_id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'content' => $blog->content,
            'thumbnail' => $blog->thumbnail,
            'author_id' => $blog->author_id,
            'published_at' => $blog->published_at,
            'status' => $blog->status,
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at
        ]);
    }

    public function update(Blog $blog): bool
    {
        $sql = "
            UPDATE blogs 
            SET category_id = :category_id, 
                title = :title, 
                slug = :slug, 
                content = :content, 
                thumbnail = :thumbnail,
                status = :status,
                updated_at = :updated_at
            WHERE id = :id
        ";

        return Database::execute($sql, [
            'id' => $blog->id,
            'category_id' => $blog->category_id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'content' => $blog->content,
            'thumbnail' => $blog->thumbnail,
            'status' => $blog->status,
            'updated_at' => $blog->updated_at
        ]);
    }

    public function updateStatus(string $id, string $status): bool
    {
        $published_at = $status === 'published' ? date('Y-m-d H:i:s') : null;
        
        $sql = "
            UPDATE blogs 
            SET status = :status, 
                published_at = :published_at, 
                updated_at = :updated_at 
            WHERE id = :id
        ";

        return Database::execute($sql, [
            'id' => $id,
            'status' => $status,
            'published_at' => $published_at,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM blogs WHERE id = :id";

        return Database::execute($sql, ['id' => $id]);
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM blogs";
        $result = Database::fetchOne($sql);

        return (int)$result->total;
    }

    public function countPublished(): int
    {
        $sql = "SELECT COUNT(*) as total FROM blogs WHERE status = 'published'";
        $result = Database::fetchOne($sql);

        return (int)$result->total;
    }

    public function search(string $keyword, int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT b.*, 
                   bc.name as category_name, bc.slug as category_slug,
                   u.name as author_name
            FROM blogs b
            LEFT JOIN blog_categories bc ON b.category_id = bc.id
            LEFT JOIN users u ON b.author_id = u.id
            WHERE b.title ILIKE :keyword OR b.content ILIKE :keyword
            ORDER BY b.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $result = Database::query($sql, [
            'keyword' => "%{$keyword}%",
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new Blog((array)$row), $result);
    }
}