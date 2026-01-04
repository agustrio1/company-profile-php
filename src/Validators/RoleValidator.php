<?php

namespace App\Validators;

class RoleValidator extends BaseValidator
{
    public function validateRole(array $data): bool
    {
        return $this->validate($data, [
            'name' => 'required|min:3|max:100',
            'description' => 'max:500',
            'permissions' => 'array'
        ]);
    }
}