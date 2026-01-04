<?php

namespace App\Validators;

class TeamValidator extends BaseValidator
{
    public function validateTeam(array $data): bool
    {
        return $this->validate($data, [
            'name' => 'required|min:2|max:255',
            'description' => 'max:1000'
        ]);
    }

    public function validateEmployee(array $data): bool
    {
        return $this->validate($data, [
            'name' => 'required|min:3|max:255',
            'position' => 'max:255',
            'bio' => 'max:2000',
            'photo' => 'image|file_max:5',
            'sort_order' => 'numeric'
        ]);
    }
}