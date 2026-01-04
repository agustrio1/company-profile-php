<?php

namespace App\Models;

use App\Traits\HasUlid;

class BlogCategory
{
    use HasUlid;

    public ?string $id = null;
    public ?string $name = null;
    public ?string $slug = null;
    public ?string $description = null;

    // Relations
    public array $blogs = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key === 'blogs' && is_string($value)) {
                    $this->$key = json_decode($value, true) ?? [];
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ];
    }
}