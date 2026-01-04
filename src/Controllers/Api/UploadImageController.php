<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Services\UploadService;

class UploadImageController
{
    private UploadService $uploadService;

    public function __construct()
    {
        $this->uploadService = new UploadService();
    }

    /**
     * Handle image upload untuk WYSIWYG Editor
     * Gambar disimpan di folder /public/uploads/editor/
     * Hanya path yang disimpan di database
     */
    public function uploadImage(Request $request): Response
    {
        // CRITICAL: Set JSON header FIRST sebelum output apapun
        header('Content-Type: application/json; charset=utf-8');
        
        // Disable error display to prevent HTML in JSON response
        ini_set('display_errors', '0');
        error_reporting(0);
        
        try {
            // Check if file was uploaded
            if (!isset($_FILES['image'])) {
                throw new \Exception('No file uploaded');
            }
            
            $file = $_FILES['image'];
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi php.ini limit)',
                    UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi form limit)',
                    UPLOAD_ERR_PARTIAL => 'Upload tidak lengkap',
                    UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
                    UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
                    UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                    UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension'
                ];
                
                $message = $errorMessages[$file['error']] ?? 'Upload error: ' . $file['error'];
                throw new \Exception($message);
            }

            // Validate file type
            if (!in_array($file['type'], ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'])) {
                throw new \Exception('Format file tidak valid. Hanya JPG, PNG, GIF, WebP yang diperbolehkan.');
            }

            // Validate file size (5MB max)
            if ($file['size'] > 5 * 1024 * 1024) {
                throw new \Exception('Ukuran file maksimal 5MB');
            }

            // Upload image using UploadService (with auto resize & WebP conversion)
            // Gambar akan disimpan di: /public/uploads/editor/
            $filename = $this->uploadService->uploadImage($file, 'editor');

            if (!$filename) {
                throw new \Exception('Gagal upload gambar');
            }

            // IMPORTANT: Pastikan path punya prefix 'uploads/' jika belum ada
            // UploadService return format bisa berbeda (kadang dengan/tanpa 'uploads/')
            if (strpos($filename, 'uploads/') !== 0) {
                $filename = 'uploads/' . ltrim($filename, '/');
            }

            $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') 
                     . '://' . $_SERVER['HTTP_HOST'];
            $url = $baseUrl . '/' . ltrim($filename, '/');

            // Get image info
            $fullPath = public_path($filename);
            
            if (!file_exists($fullPath)) {
                throw new \Exception('File uploaded but not found: ' . $filename);
            }
            
            $imageInfo = @getimagesize($fullPath);

            // Return success JSON
            $response = [
                'success' => true,
                'url' => $url,
                'path' => $filename,
                'filename' => basename($filename),
                'size' => filesize($fullPath),
                'dimensions' => [
                    'width' => $imageInfo[0] ?? null,
                    'height' => $imageInfo[1] ?? null
                ]
            ];

            echo json_encode($response);
            exit;

        } catch (\Exception $e) {
            http_response_code(400);
            
            $errorResponse = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            
            echo json_encode($errorResponse);
            exit;
        }
    }
}