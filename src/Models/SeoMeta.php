<?php

namespace App\Models;

use App\Traits\HasUlid;

class SeoMeta
{
    use HasUlid;

    public ?string $id = null;
    public ?string $model = null;
    public ?string $model_id = null;
    public ?string $title = null;
    public ?string $description = null; 
    public ?string $keywords = null;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            // Map database columns to properties
            if ($key === 'meta_title') {
                $this->title = $value;
            } elseif ($key === 'meta_description') {
                $this->description = $value;
            } elseif ($key === 'meta_keywords') {
                $this->keywords = $value;
            } elseif (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'model' => $this->model,
            'model_id' => $this->model_id,
            'meta_title' => $this->title,
            'meta_description' => $this->description,
            'meta_keywords' => $this->keywords,
        ];
    }
}