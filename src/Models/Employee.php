<?php

namespace App\Models;

use App\Traits\HasUlid;

class Employee
{
    use HasUlid;

    public ?string $id = null;
    public ?string $team_id = null;
    public ?string $name = null;
    public ?string $position = null;
    public ?string $photo = null;
    public ?string $bio = null;
    public ?int $sort_order = 0;

    // Relations
    public ?object $team = null;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                // Handle integer conversion
                if ($key === 'sort_order') {
                    $this->$key = (int)$value;
                } elseif ($key === 'team' && is_string($value)) {
                    // Handle if team comes as JSON string
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
            'team_id' => $this->team_id,
            'name' => $this->name,
            'position' => $this->position,
            'photo' => $this->photo,
            'bio' => $this->bio,
            'sort_order' => $this->sort_order,
        ];
    }

    public function hasPhoto(): bool
    {
        return !empty($this->photo);
    }

    public function getPhotoUrl(): ?string
    {
        if (!$this->photo) {
            return null;
        }

        $path = $this->photo;
        if (strpos($path, 'uploads/') !== 0) {
            $path = 'uploads/' . ltrim($path, '/');
        }

        return asset($path);
    }
}