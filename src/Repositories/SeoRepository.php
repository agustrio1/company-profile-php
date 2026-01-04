<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\SeoMeta;

class SeoRepository
{
    public function findByModel(string $model, string $modelId): ?SeoMeta
    {
        $sql = "
            SELECT * FROM seo_meta 
            WHERE model = :model AND model_id = :model_id 
            LIMIT 1
        ";
        
        $result = Database::fetchOne($sql, [
            'model' => $model,
            'model_id' => $modelId
        ]);

        if (!$result) {
            return null;
        }

        return new SeoMeta((array)$result);
    }

    public function create(SeoMeta $seo): bool
    {
        $sql = "
            INSERT INTO seo_meta (id, model, model_id, meta_title, meta_description, meta_keywords)
            VALUES (:id, :model, :model_id, :meta_title, :meta_description, :meta_keywords)
        ";

        $params = [
            'id' => $seo->id,
            'model' => $seo->model,
            'model_id' => $seo->model_id,
            'meta_title' => $seo->title,
            'meta_description' => $seo->description,
            'meta_keywords' => $seo->keywords
        ];

        error_log("Creating SEO - params sent to DB: " . print_r($params, true));

        return Database::execute($sql, $params);
    }

    public function update(SeoMeta $seo): bool
    {
        $sql = "
            UPDATE seo_meta 
            SET meta_title = :meta_title, 
                meta_description = :meta_description, 
                meta_keywords = :meta_keywords
            WHERE id = :id
        ";

        $params = [
            'id' => $seo->id,
            'meta_title' => $seo->title,
            'meta_description' => $seo->description,
            'meta_keywords' => $seo->keywords
        ];

        error_log("Updating SEO - params sent to DB: " . print_r($params, true));

        return Database::execute($sql, $params);
    }

    public function upsert(string $model, string $modelId, array $data): bool
    {
        error_log("=== SEO UPSERT DEBUG ===");
        error_log("Model: $model, ModelId: $modelId");
        error_log("Data received in upsert: " . print_r($data, true));

        $existing = $this->findByModel($model, $modelId);

        if ($existing) {
            error_log("SEO exists, updating. Current data: " . print_r($existing, true));
            
            $existing->title = $data['title'] ?? $existing->title;
            $existing->description = $data['description'] ?? $existing->description;
            $existing->keywords = $data['keywords'] ?? $existing->keywords;
            
            error_log("SEO after mapping: " . print_r($existing, true));
            
            return $this->update($existing);
        }

        error_log("SEO not exists, creating new");

        $seo = new SeoMeta([
            'model' => $model,
            'model_id' => $modelId,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'keywords' => $data['keywords'] ?? null
        ]);
        
        $seo->setUlid();

        error_log("New SEO object before save: " . print_r($seo, true));

        return $this->create($seo);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM seo_meta WHERE id = :id";
        return Database::execute($sql, ['id' => $id]);
    }

    public function deleteByModel(string $model, string $modelId): bool
    {
        $sql = "DELETE FROM seo_meta WHERE model = :model AND model_id = :model_id";
        return Database::execute($sql, [
            'model' => $model,
            'model_id' => $modelId
        ]);
    }
}