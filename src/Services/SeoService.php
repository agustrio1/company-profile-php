<?php

namespace App\Services;

use App\Models\SeoMeta;
use App\Repositories\SeoRepository;

class SeoService
{
    private SeoRepository $seoRepo;

    public function __construct()
    {
        $this->seoRepo = new SeoRepository();
    }

    public function getByModel(string $model, string $modelId): ?SeoMeta
    {
        return $this->seoRepo->findByModel($model, $modelId);
    }

    public function create(string $model, string $modelId, array $data): ?SeoMeta
    {
        $seo = new SeoMeta([
            'model' => $model,
            'model_id' => $modelId,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null
        ]);

        $seo->setUlid();

        if ($this->seoRepo->create($seo)) {
            return $seo;
        }

        return null;
    }

    public function update(string $id, array $data): ?SeoMeta
    {
        $seo = $this->seoRepo->findByModel($data['model'], $data['model_id']);

        if (!$seo) {
            return null;
        }

        $seo->meta_title = $data['meta_title'] ?? null;
        $seo->meta_description = $data['meta_description'] ?? null;
        $seo->meta_keywords = $data['meta_keywords'] ?? null;

        if ($this->seoRepo->update($seo)) {
            return $seo;
        }

        return null;
    }

    public function upsert(string $model, string $modelId, array $data): bool
    {
        return $this->seoRepo->upsert($model, $modelId, $data);
    }

    public function delete(string $id): bool
    {
        return $this->seoRepo->delete($id);
    }

    public function deleteByModel(string $model, string $modelId): bool
    {
        return $this->seoRepo->deleteByModel($model, $modelId);
    }
}