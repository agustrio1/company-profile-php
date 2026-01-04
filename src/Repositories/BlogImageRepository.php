<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\BlogImage;

class BlogImageRepository
{
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT * FROM blog_images ORDER BY sort_order ASC LIMIT :limit OFFSET :offset";
        
        $result = Database::query($sql, [
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new BlogImage((array)$row), $result);
    }

    public function findByBlogId(string $blogId): array
    {
        $sql = "
            SELECT * FROM blog_images 
            WHERE blog_id = :blog_id 
            ORDER BY sort_order ASC
        ";
        
        $result = Database::query($sql, ['blog_id' => $blogId])->fetchAll();

        return array_map(fn($row) => new BlogImage((array)$row), $result);
    }

    public function findById(string $id): ?BlogImage
    {
        $sql = "SELECT * FROM blog_images WHERE id = :id LIMIT 1";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new BlogImage((array)$result) : null;
    }

    public function create(BlogImage $image): bool
    {
        $sql = "
            INSERT INTO blog_images (id, blog_id, image, caption, sort_order)
            VALUES (:id, :blog_id, :image, :caption, :sort_order)
        ";

        return Database::execute($sql, [
            'id' => $image->id,
            'blog_id' => $image->blog_id,
            'image' => $image->image,
            'caption' => $image->caption,
            'sort_order' => $image->sort_order
        ]);
    }

    public function update(BlogImage $image): bool
    {
        $sql = "
            UPDATE blog_images 
            SET image = :image, 
                caption = :caption, 
                sort_order = :sort_order
            WHERE id = :id
        ";

        return Database::execute($sql, [
            'id' => $image->id,
            'image' => $image->image,
            'caption' => $image->caption,
            'sort_order' => $image->sort_order
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM blog_images WHERE id = :id";

        return Database::execute($sql, ['id' => $id]);
    }

    public function deleteByBlogId(string $blogId): bool
    {
        $sql = "DELETE FROM blog_images WHERE blog_id = :blog_id";

        return Database::execute($sql, ['blog_id' => $blogId]);
    }

    public function updateSortOrder(string $id, int $sortOrder): bool
    {
        $sql = "UPDATE blog_images SET sort_order = :sort_order WHERE id = :id";

        return Database::execute($sql, [
            'id' => $id,
            'sort_order' => $sortOrder
        ]);
    }

    public function bulkCreate(string $blogId, array $images): bool
    {
        Database::beginTransaction();

        try {
            foreach ($images as $index => $imageData) {
                $image = new BlogImage([
                    'blog_id' => $blogId,
                    'image' => $imageData['image'],
                    'caption' => $imageData['caption'] ?? null,
                    'sort_order' => $imageData['sort_order'] ?? $index
                ]);

                $image->setUlid();
                $this->create($image);
            }

            Database::commit();
            return true;
        } catch (\Exception $e) {
            Database::rollback();
            return false;
        }
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM blog_images";
        $result = Database::fetchOne($sql);

        return (int)$result->total;
    }

    public function countByBlogId(string $blogId): int
    {
        $sql = "SELECT COUNT(*) as total FROM blog_images WHERE blog_id = :blog_id";
        $result = Database::fetchOne($sql, ['blog_id' => $blogId]);

        return (int)$result->total;
    }

    public function search(string $keyword, int $limit = 50, int $offset = 0): array
    {
        $sql = "
            SELECT * FROM blog_images 
            WHERE caption ILIKE :keyword
            ORDER BY sort_order ASC
            LIMIT :limit OFFSET :offset
        ";

        $result = Database::query($sql, [
            'keyword' => "%{$keyword}%",
            'limit' => $limit,
            'offset' => $offset
        ])->fetchAll();

        return array_map(fn($row) => new BlogImage((array)$row), $result);
    }
}