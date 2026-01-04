<?php

namespace App\Validators;

class AuthValidator extends BaseValidator
{
    public function validateLogin(array $data): bool
    {
        return $this->validate($data, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }

    public function validateRegister(array $data): bool
    {
        return $this->validate($data, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|strong_password|confirmed'
        ]);
    }

    public function validateForgotPassword(array $data): bool
    {
        return $this->validate($data, [
            'email' => 'required|email'
        ]);
    }

    public function validateResetPassword(array $data): bool
    {
        return $this->validate($data, [
            'token' => 'required',
            'password' => 'required|min:8|strong_password|confirmed'
        ]);
    }
}