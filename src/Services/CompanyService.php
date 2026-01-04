<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;

class CompanyService
{
    private CompanyRepository $companyRepo;
    private UploadService $uploadService;
    private SeoService $seoService;

    public function __construct()
    {
        $this->companyRepo = new CompanyRepository();
        $this->uploadService = new UploadService();
        $this->seoService = new SeoService();
    }

    public function get(): ?Company
    {
        $company = $this->companyRepo->findFirst();

        if ($company) {
            $company->seo = $this->seoService->getByModel('company', $company->id);
        }

        return $company;
    }

    public function getById(string $id): ?Company
    {
        $company = $this->companyRepo->findById($id);

        if ($company) {
            $company->seo = $this->seoService->getByModel('company', $company->id);
        }

        return $company;
    }

    public function exists(): bool
    {
        return $this->companyRepo->exists();
    }

    public function createOrUpdate(array $data, ?array $logo = null): ?Company
    {
        $existing = $this->companyRepo->findFirst();

        if ($existing) {
            return $this->update($existing->id, $data, $logo);
        }

        return $this->create($data, $logo);
    }

    public function create(array $data, ?array $logo = null): ?Company
    {
        $company = new Company([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? slugify($data['name']),
            'description' => $data['description'] ?? null,
            'vision' => $data['vision'] ?? null,
            'mission' => $data['mission'] ?? null,
            'founded_year' => $data['founded_year'] ?? null,
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null
        ]);

        $company->setUlid();
        $company->touchTimestamps();

        if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
            $company->logo = $this->uploadService->uploadImage($logo, 'company');
        }

        if ($this->companyRepo->create($company)) {
            if (!empty($data['seo'])) {
                $this->seoService->upsert('company', $company->id, $data['seo']);
            }

            return $company;
        }

        return null;
    }

    public function update(string $id, array $data, ?array $logo = null): ?Company
    {
        $company = $this->companyRepo->findById($id);

        if (!$company) {
            return null;
        }

        $company->name = $data['name'];
        $company->slug = $data['slug'] ?? slugify($data['name']);
        $company->description = $data['description'] ?? null;
        $company->vision = $data['vision'] ?? null;
        $company->mission = $data['mission'] ?? null;
        $company->founded_year = $data['founded_year'] ?? null;
        $company->address = $data['address'] ?? null;
        $company->phone = $data['phone'] ?? null;
        $company->email = $data['email'] ?? null;
        $company->website = $data['website'] ?? null;
        $company->setUpdatedAt();

        if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
            if ($company->logo) {
                $this->uploadService->deleteFile($company->logo);
            }
            
            $company->logo = $this->uploadService->uploadImage($logo, 'company');
        }

        if ($this->companyRepo->update($company)) {
            if (!empty($data['seo'])) {
                $this->seoService->upsert('company', $company->id, $data['seo']);
            }

            return $company;
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $company = $this->companyRepo->findById($id);

        if (!$company) {
            return false;
        }

        if ($company->logo) {
            $this->uploadService->deleteFile($company->logo);
        }

        $this->seoService->deleteByModel('company', $id);

        return $this->companyRepo->delete($id);
    }
}