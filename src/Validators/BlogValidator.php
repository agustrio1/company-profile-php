<?php

namespace App\Validators;

class BlogValidator extends BaseValidator
{
    public function validateCreate(array $data): bool
    {
        return $this->validate($data, [
            'title' => 'required|min:3|max:255',
            'slug' => 'max:255',
            'content' => 'required',
            'category_id' => 'required',
            'status' => 'required',
            'thumbnail' => 'image|file_max:5'
        ]);
    }

    public function validateUpdate(array $data): bool
    {
        return $this->validate($data, [
            'title' => 'required|min:3|max:255',
            'slug' => 'max:255',
            'content' => 'required',
            'status' => 'required',
            'thumbnail' => 'image|file_max:5'
        ]);
    }

    public function validateCategory(array $data): bool
    {
        return $this->validate($data, [
            'name' => 'required|min:3|max:255',
            'slug' => 'max:255',
            'description' => 'max:1000'
        ]);
    }
}