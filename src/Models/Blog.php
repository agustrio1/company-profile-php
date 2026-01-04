<?php

namespace App\Models;

use App\Traits\HasUlid;
use App\Traits\HasTimestamps;

class Blog
{
    use HasUlid, HasTimestamps;

    public ?string $id = null;
    public ?string $category_id = null;
    public ?string $title = null;
    public ?string $slug = null;
    public ?string $content = null;
    public ?string $thumbnail = null;
    public ?string $author_id = null;
    public ?string $published_at = null;
    public ?string $status = 'draft'; // draft, published, archived
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // Relations
    public ?BlogCategory $category = null;
    public ?object $author = null;
    public array $images = [];
    public ?object $seo = null;

    // Extra fields from JOIN
    public ?string $category_name = null;
    public ?string $category_slug = null;
    public ?string $author_name = null;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                // Handle JSON fields
                if ($key === 'images' && is_string($value)) {
                    $this->$key = json_decode($value, true) ?? [];
                } elseif ($key === 'seo' && is_string($value)) {
                    $this->$key = json_decode($value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail,
            'author_id' => $this->author_id,
            'published_at' => $this->published_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    public function publish(): void
    {
        $this->status = 'published';
        $this->published_at = date('Y-m-d H:i:s');
    }

    public function hasThumbnail(): bool
    {
        return !empty($this->thumbnail);
    }

    public function getThumbnailUrl(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        $path = $this->thumbnail;
        
        // Jika path tidak diawali dengan 'uploads/', tambahkan
        if (strpos($path, 'uploads/') !== 0) {
            $path = 'uploads/' . ltrim($path, '/');
        }

        return asset($path);
    }

    public function getExcerpt(int $length = 160): string
    {
        $text = strip_tags($this->content);
        
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . '...';
    }

    public function getReadingTime(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200);
        
        return max(1, $minutes);
    }
}