<?php

namespace App\Models;

use App\Traits\HasUlid;
use App\Traits\HasTimestamps;

class Service
{
    use HasUlid, HasTimestamps;

    public ?string $id = null;
    public ?string $title = null;
    public ?string $slug = null;
    public ?string $description = null;
    public ?string $icon = null;
    public ?string $image = null;
    public ?bool $is_featured = false;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // Relations
    public ?object $seo = null;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key === 'is_featured') {
                    $this->$key = filter_var($value, FILTER_VALIDATE_BOOLEAN);
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'image' => $this->image,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function isFeatured(): bool
    {
        return $this->is_featured === true;
    }

    public function setFeatured(bool $featured): void
    {
        $this->is_featured = $featured;
    }
}