<?php

namespace App\Models;

use App\Traits\HasUlid;

class BlogImage
{
    use HasUlid;

    public ?string $id = null;
    public ?string $blog_id = null;
    public ?string $image = null;
    public ?string $caption = null;
    public ?int $sort_order = 0;

    // Relations
    public ?Blog $blog = null;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                // Handle JSON or special fields jika perlu
                if ($key === 'blog' && is_string($value)) {
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
            'blog_id' => $this->blog_id,
            'image' => $this->image,
            'caption' => $this->caption,
            'sort_order' => $this->sort_order,
        ];
    }


    public function hasImage(): bool
    {
        return !empty($this->image);
    }

    public function getImageUrl(): ?string
    {
        if (!$this->image) {
            return null;
        }

        $path = $this->image;
        
        // Jika path tidak diawali dengan 'uploads/', tambahkan
        if (strpos($path, 'uploads/') !== 0) {
            $path = 'uploads/' . ltrim($path, '/');
        }

        return asset($path);
    }

    public function hasCaption(): bool
    {
        return !empty($this->caption);
    }
}