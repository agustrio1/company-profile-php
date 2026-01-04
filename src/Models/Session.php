<?php

namespace App\Models;

use App\Traits\HasUlid;

class Session
{
    use HasUlid;

    public ?string $id = null;
    public ?string $user_id = null;
    public ?string $token = null;
    public ?string $ip_address = null;
    public ?string $user_agent = null;
    public ?string $last_activity = null;
    public ?string $expires_at = null;
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
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'last_activity' => $this->last_activity,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
        ];
    }

    public function isExpired(): bool
    {
        return strtotime($this->expires_at) < time();
    }
}