<?php

namespace App\Services;

use App\Models\Service;
use App\Repositories\ServiceRepository;

class CompanyServiceService
{
    private ServiceRepository $serviceRepo;
    private UploadService $uploadService;
    private SeoService $seoService;

    public function __construct()
    {
        $this->serviceRepo = new ServiceRepository();
        $this->uploadService = new UploadService();
        $this->seoService = new SeoService();
    }

    public function getAll(int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $services = $this->serviceRepo->findAll($perPage, $offset);
        $total = $this->serviceRepo->count();

        return [
            'data' => $services,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function getFeatured(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $services = $this->serviceRepo->findFeatured($perPage, $offset);
        $total = $this->serviceRepo->countFeatured();

        return [
            'data' => $services,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function getById(string $id): ?Service
    {
        $service = $this->serviceRepo->findById($id);

        if ($service) {
            $service->seo = $this->seoService->getByModel('services', $service->id);
        }

        return $service;
    }

    public function getBySlug(string $slug): ?Service
    {
        $service = $this->serviceRepo->findBySlug($slug);

        if ($service) {
            $service->seo = $this->seoService->getByModel('services', $service->id);
        }

        return $service;
    }

    public function create(array $data, ?array $icon = null, ?array $image = null): ?Service
    {
        $service = new Service([
            'title' => $data['title'],
            'slug' => $data['slug'] ?? slugify($data['title']),
            'description' => $data['description'] ?? null,
            'is_featured' => $data['is_featured'] ?? false
        ]);

        $service->setUlid();
        $service->touchTimestamps();

        if ($icon && $icon['error'] === UPLOAD_ERR_OK) {
            $service->icon = $this->uploadService->uploadImage($icon, 'services/icons');
        }

        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $service->image = $this->uploadService->uploadImage($image, 'services');
        }

        if ($this->serviceRepo->create($service)) {
            if (!empty($data['seo'])) {
                $this->seoService->upsert('services', $service->id, $data['seo']);
            }

            return $service;
        }

        return null;
    }

    public function update(string $id, array $data, ?array $icon = null, ?array $image = null): ?Service
    {
        $service = $this->serviceRepo->findById($id);

        if (!$service) {
            return null;
        }

        $service->title = $data['title'];
        $service->slug = $data['slug'] ?? slugify($data['title']);
        $service->description = $data['description'] ?? null;
        $service->is_featured = $data['is_featured'] ?? false;
        $service->setUpdatedAt();

        if ($icon && $icon['error'] === UPLOAD_ERR_OK) {
            if ($service->icon) {
                $this->uploadService->deleteFile($service->icon);
            }
            
            $service->icon = $this->uploadService->uploadImage($icon, 'services/icons');
        }

        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            if ($service->image) {
                $this->uploadService->deleteFile($service->image);
            }
            
            $service->image = $this->uploadService->uploadImage($image, 'services');
        }

        if ($this->serviceRepo->update($service)) {
            if (!empty($data['seo'])) {
                $this->seoService->upsert('services', $service->id, $data['seo']);
            }

            return $service;
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $service = $this->serviceRepo->findById($id);

        if (!$service) {
            return false;
        }

        if ($service->icon) {
            $this->uploadService->deleteFile($service->icon);
        }

        if ($service->image) {
            $this->uploadService->deleteFile($service->image);
        }

        $this->seoService->deleteByModel('services', $id);

        return $this->serviceRepo->delete($id);
    }

    public function search(string $keyword, int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $services = $this->serviceRepo->search($keyword, $perPage, $offset);

        return [
            'data' => $services,
            'keyword' => $keyword
        ];
    }
}