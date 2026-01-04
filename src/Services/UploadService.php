<?php

namespace App\Services;

class UploadService
{
    private string $uploadPath;
    private int $maxSize;
    private array $allowedExtensions;

    public function __construct()
    {
        $this->uploadPath = public_path('uploads/');
        $this->maxSize = config('app.upload.max_size', 5242880); // 5MB default
        $this->allowedExtensions = config('app.upload.allowed_extensions', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }

    public function uploadImage(array $file, string $folder = ''): ?string
    {
        if (!$this->validateFile($file)) {
            return null;
        }

        $filename = $this->generateFilename($file['name']);
        $destination = $this->uploadPath . $folder;

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $filePath = $destination . '/' . $filename;

        // Convert to WebP
        if ($this->convertToWebP($file['tmp_name'], $filePath)) {
            return $folder . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
        }

        return null;
    }

    public function uploadMultiple(array $files, string $folder = ''): array
    {
        $uploaded = [];

        foreach ($files['name'] as $key => $name) {
            $file = [
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            ];

            $path = $this->uploadImage($file, $folder);
            if ($path) {
                $uploaded[] = $path;
            }
        }

        return $uploaded;
    }

    private function convertToWebP(string $source, string $destination): bool
    {
        $imageType = exif_imagetype($source);
        $image = null;

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($source);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($source);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($source);
                break;
            case IMAGETYPE_WEBP:
                // Already WebP, just copy
                return copy($source, $destination);
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        // Convert to WebP
        $webpPath = pathinfo($destination, PATHINFO_DIRNAME) . '/' . 
                    pathinfo($destination, PATHINFO_FILENAME) . '.webp';

        $result = imagewebp($image, $webpPath, 80); // 80% quality
        
        // imagedestroy() removed - deprecated since PHP 8.5
        // GD resources are automatically destroyed since PHP 8.0
        // No manual cleanup needed anymore

        return $result;
    }

    private function validateFile(array $file): bool
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        if ($file['size'] > $this->maxSize) {
            return false;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            return false;
        }

        return true;
    }

    private function generateFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $name = slugify($name);
        
        return $name . '_' . time() . '_' . uniqid() . '.' . $extension;
    }

    public function deleteFile(string $path): bool
    {
        $fullPath = public_path($path);
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    public function deleteMultiple(array $paths): bool
    {
        $success = true;

        foreach ($paths as $path) {
            if (!$this->deleteFile($path)) {
                $success = false;
            }
        }

        return $success;
    }
}