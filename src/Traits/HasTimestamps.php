<?php

namespace App\Traits;

trait HasTimestamps
{
    public function setCreatedAt(): void
    {
        if (empty($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s');
        }
    }

    public function setUpdatedAt(): void
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function touchTimestamps(): void
    {
        $this->setCreatedAt();
        $this->setUpdatedAt();
    }
}