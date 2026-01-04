<?php

namespace App\Models;

use App\Traits\HasUlid;
use App\Traits\HasTimestamps;

class Company
{
    use HasUlid, HasTimestamps;

    public ?string $id = null;
    public ?string $name = null;
    public ?string $slug = null;
    public ?string $description = null;
    public ?string $vision = null;
    public ?string $mission = null;
    public ?string $logo = null;
    public ?int $founded_year = null;
    public ?string $address = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $website = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // Relations
    public ?object $seo = null;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                // Handle integer conversion
                if ($key === 'founded_year') {
                    $this->$key = $value ? (int)$value : null;
                } elseif ($key === 'seo' && is_string($value)) {
                    // Handle if SEO comes as JSON string
                    $this->$key = json_decode($value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'vision' => $this->vision,
            'mission' => $this->mission,
            'logo' => $this->logo,
            'founded_year' => $this->founded_year,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function hasLogo(): bool
    {
        return !empty($this->logo);
    }

    public function getLogoUrl(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        $path = $this->logo;
        if (strpos($path, 'uploads/') !== 0) {
            $path = 'uploads/' . ltrim($path, '/');
        }

        return asset($path);
    }

    public function getFoundedYear(): ?int
    {
        return $this->founded_year;
    }

    public function getAge(): ?int
    {
        if (!$this->founded_year) {
            return null;
        }

        return (int)date('Y') - $this->founded_year;
    }
}