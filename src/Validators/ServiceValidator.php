<?php

namespace App\Validators;

class ServiceValidator extends BaseValidator
{
    public function validateCreate(array $data): bool
    {
        return $this->validate($data, [
            'title' => 'required|min:3|max:255',
            'slug' => 'max:255',
            'description' => 'max:5000',
            'icon' => 'image|file_max:2',
            'image' => 'image|file_max:5'
        ]);
    }

    public function validateUpdate(array $data): bool
    {
        return $this->validate($data, [
            'title' => 'required|min:3|max:255',
            'slug' => 'max:255',
            'description' => 'max:5000',
            'icon' => 'image|file_max:2',
            'image' => 'image|file_max:5'
        ]);
    }
}