<?php

namespace App\Traits;

use Ulid\Ulid;

trait HasUlid
{
    public function generateUlid(): string
    {
        return strtolower(Ulid::generate());
    }

    public function setUlid(): void
    {
        if (empty($this->id)) {
            $this->id = $this->generateUlid();
        }
    }

    public static function newUlid(): string
    {
        return strtolower(Ulid::generate());
    }
}