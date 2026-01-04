<?php

namespace App\Validators;

class UserValidator extends BaseValidator
{
    public function validateCreate(array $data): bool
    {
        return $this->validate($data, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
            'roles' => 'array'
        ]);
    }

    public function validateUpdate(array $data): bool
    {
        return $this->validate($data, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255',
            'roles' => 'array'
        ]);
    }
}