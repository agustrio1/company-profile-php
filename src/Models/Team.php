<?php

namespace App\Models;

use App\Traits\HasUlid;

class Team
{
    use HasUlid;

    public ?string $id = null;
    public ?string $name = null;
    public ?string $description = null;

    // Relations
    public array $employees = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                // Handle array fields
                if ($key === 'employees') {
                    if (is_string($value)) {
                        $this->$key = json_decode($value, true) ?? [];
                    } elseif (is_array($value)) {
                        $this->$key = $value;
                    } else {
                        // If null or other type, keep as empty array
                        $this->$key = [];
                    }
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

    public function hasEmployees(): bool
    {
        return !empty($this->employees);
    }

    public function getEmployeeCount(): int
    {
        return count($this->employees);
    }
}