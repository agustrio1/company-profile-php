<?php

namespace App\Validators;

class CompanyValidator extends BaseValidator
{
    public function validateCompany(array $data): bool
    {
        return $this->validate($data, [
            'name' => 'required|min:3|max:255',
            'slug' => 'max:255',
            'description' => 'max:5000',
            'vision' => 'max:2000',
            'mission' => 'max:2000',
            'founded_year' => 'numeric',
            'address' => 'max:500',
            'phone' => 'max:50',
            'email' => 'email|max:255',
            'website' => 'url|max:255',
            'logo' => 'image|file_max:5'
        ]);
    }
}