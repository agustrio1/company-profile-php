<?php

namespace App\Models;

use App\Traits\HasUlid;
use App\Traits\HasTimestamps;

class User
{
    use HasUlid, HasTimestamps;

    public ?string $id = null;
    public ?string $name = null;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // Relations
    public array $roles = [];
    public array $sessions = [];
    public array $blogs = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (in_array($key, ['roles', 'sessions', 'blogs']) && is_string($value)) {
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
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}