<?php

namespace App\Models;

use App\Traits\HasUlid;

class Permission
{
    use HasUlid;

    public ?string $id = null;
    public ?string $name = null;
    public ?string $description = null;

    // Relations
    public array $roles = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}