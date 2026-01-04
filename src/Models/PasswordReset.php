<?php

namespace App\Models;

use App\Traits\HasUlid;

class PasswordReset
{
    use HasUlid;

    public ?string $id = null;
    public ?string $user_id = null;
    public ?string $token = null;
    public ?string $expires_at = null;
    public ?string $used_at = null;
    public ?string $created_at = null;

    // Relations
    public ?User $user = null;

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
            'user_id' => $this->user_id,
            'token' => $this->token,
            'expires_at' => $this->expires_at,
            'used_at' => $this->used_at,
            'created_at' => $this->created_at,
        ];
    }

    public function isExpired(): bool
    {
        return strtotime($this->expires_at) < time();
    }

    public function isUsed(): bool
    {
        return !empty($this->used_at);
    }

    public function markAsUsed(): void
    {
        $this->used_at = date('Y-m-d H:i:s');
    }
}