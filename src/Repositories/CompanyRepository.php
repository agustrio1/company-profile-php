<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Company;

class CompanyRepository
{
    public function findFirst(): ?Company
    {
        $sql = "SELECT * FROM company LIMIT 1";
        
        $result = Database::fetchOne($sql);

        return $result ? new Company((array)$result) : null;
    }

    public function findById(string $id): ?Company
    {
        $sql = "SELECT * FROM company WHERE id = :id LIMIT 1";
        
        $result = Database::fetchOne($sql, ['id' => $id]);

        return $result ? new Company((array)$result) : null;
    }

    public function findBySlug(string $slug): ?Company
    {
        $sql = "SELECT * FROM company WHERE slug = :slug LIMIT 1";
        
        $result = Database::fetchOne($sql, ['slug' => $slug]);

        return $result ? new Company((array)$result) : null;
    }

    public function exists(): bool
    {
        $sql = "SELECT COUNT(*) as total FROM company";
        $result = Database::fetchOne($sql);

        return (int)$result->total > 0;
    }

    public function create(Company $company): bool
    {
        $sql = "
            INSERT INTO company (id, name, slug, description, vision, mission, logo, founded_year, address, phone, email, website, created_at, updated_at)
            VALUES (:id, :name, :slug, :description, :vision, :mission, :logo, :founded_year, :address, :phone, :email, :website, :created_at, :updated_at)
        ";

        return Database::execute($sql, [
            'id' => $company->id,
            'name' => $company->name,
            'slug' => $company->slug,
            'description' => $company->description,
            'vision' => $company->vision,
            'mission' => $company->mission,
            'logo' => $company->logo,
            'founded_year' => $company->founded_year,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
            'website' => $company->website,
            'created_at' => $company->created_at,
            'updated_at' => $company->updated_at
        ]);
    }

    public function update(Company $company): bool
    {
        $sql = "
            UPDATE company 
            SET name = :name, 
                slug = :slug, 
                description = :description, 
                vision = :vision, 
                mission = :mission, 
                logo = :logo, 
                founded_year = :founded_year, 
                address = :address, 
                phone = :phone, 
                email = :email, 
                website = :website,
                updated_at = :updated_at
            WHERE id = :id
        ";

        return Database::execute($sql, [
            'id' => $company->id,
            'name' => $company->name,
            'slug' => $company->slug,
            'description' => $company->description,
            'vision' => $company->vision,
            'mission' => $company->mission,
            'logo' => $company->logo,
            'founded_year' => $company->founded_year,
            'address' => $company->address,
            'phone' => $company->phone,
            'email' => $company->email,
            'website' => $company->website,
            'updated_at' => $company->updated_at
        ]);
    }

    public function createOrUpdate(Company $company): bool
    {
        if ($this->exists()) {
            $existing = $this->findFirst();
            $company->id = $existing->id;
            return $this->update($company);
        }
        
        return $this->create($company);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM company WHERE id = :id";

        return Database::execute($sql, ['id' => $id]);
    }
}