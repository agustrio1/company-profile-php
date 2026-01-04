<?php

namespace App\Models;

use App\Traits\HasUlid;

class Role
{
    use HasUlid;

    public ?string $id = null;
    public ?string $name = null;
    public ?string $description = null;

    // Relations
    public array $permissions = [];
    public array $users = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                
                if (in_array($key, ['permissions', 'users']) && is_string($value)) {
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
            'description' => $this->description,
        ];
    }
}