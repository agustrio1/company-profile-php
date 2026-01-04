<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\BlogImage;
use App\Repositories\BlogRepository;
use App\Repositories\BlogImageRepository;

class BlogService
{
    private BlogRepository $blogRepo;
    private BlogImageRepository $imageRepo;
    private UploadService $uploadService;
    private SeoService $seoService;

    public function __construct()
    {
        $this->blogRepo = new BlogRepository();
        $this->imageRepo = new BlogImageRepository();
        $this->uploadService = new UploadService();
        $this->seoService = new SeoService();
    }

    public function getAll(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $blogs = $this->blogRepo->findAll($perPage, $offset);
        $total = $this->blogRepo->count();

        return [
            'data' => $blogs,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function getPublished(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $blogs = $this->blogRepo->findPublished($perPage, $offset);
        $total = $this->blogRepo->countPublished();

        return [
            'data' => $blogs,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function getBySlug(string $slug): ?Blog
    {
        $blog = $this->blogRepo->findBySlug($slug);

        if ($blog) {
            $blog->images = $this->imageRepo->findByBlogId($blog->id);
            $blog->seo = $this->seoService->getByModel('blogs', $blog->id);
        }

        return $blog;
    }

    public function getById(string $id): ?Blog
    {
        $blog = $this->blogRepo->findById($id);

        if ($blog) {
            $blog->images = $this->imageRepo->findByBlogId($blog->id);
            $blog->seo = $this->seoService->getByModel('blogs', $blog->id);
            
            error_log("Blog loaded with SEO: " . print_r($blog->seo, true));
        }

        return $blog;
    }

    public function create(array $data, ?array $thumbnail = null, ?array $additionalImages = null): ?Blog
    {
        $blog = new Blog([
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'],
            'slug' => $data['slug'] ?? slugify($data['title']),
            'content' => $data['content'],
            'author_id' => $data['author_id'],
            'status' => $data['status'] ?? 'draft'
        ]);

        $blog->setUlid();
        $blog->touchTimestamps();

        if ($thumbnail && $thumbnail['error'] === UPLOAD_ERR_OK) {
            $blog->thumbnail = $this->uploadService->uploadImage($thumbnail, 'blogs');
        }

        if ($this->blogRepo->create($blog)) {
            if ($additionalImages) {
                $this->uploadAdditionalImages($blog->id, $additionalImages);
            }

            if (!empty($data['seo'])) {
                error_log("Saving SEO data: " . print_r($data['seo'], true));
                $saved = $this->seoService->upsert('blogs', $blog->id, $data['seo']);
                error_log("SEO save result: " . ($saved ? 'SUCCESS' : 'FAILED'));
            }

            return $blog;
        }

        return null;
    }

    public function update(string $id, array $data, ?array $thumbnail = null, ?array $additionalImages = null): ?Blog
    {
        $blog = $this->blogRepo->findById($id);

        if (!$blog) {
            return null;
        }

        $blog->category_id = $data['category_id'] ?? $blog->category_id;
        $blog->title = $data['title'];
        $blog->slug = $data['slug'] ?? slugify($data['title']);
        $blog->content = $data['content'];
        $blog->status = $data['status'] ?? $blog->status;
        $blog->setUpdatedAt();

        if ($thumbnail && $thumbnail['error'] === UPLOAD_ERR_OK) {
            if ($blog->thumbnail) {
                $this->uploadService->deleteFile($blog->thumbnail);
            }
            
            $blog->thumbnail = $this->uploadService->uploadImage($thumbnail, 'blogs');
        }

        if ($this->blogRepo->update($blog)) {
            if ($additionalImages) {
                $this->uploadAdditionalImages($blog->id, $additionalImages);
            }

            if (!empty($data['seo'])) {
                error_log("Updating SEO data: " . print_r($data['seo'], true));
                $saved = $this->seoService->upsert('blogs', $blog->id, $data['seo']);
                error_log("SEO update result: " . ($saved ? 'SUCCESS' : 'FAILED'));
            }

            return $blog;
        }

        return null;
    }

    public function publish(string $id): bool
    {
        return $this->blogRepo->updateStatus($id, 'published');
    }

    public function unpublish(string $id): bool
    {
        return $this->blogRepo->updateStatus($id, 'draft');
    }

    public function archive(string $id): bool
    {
        return $this->blogRepo->updateStatus($id, 'archived');
    }

    public function delete(string $id): bool
    {
        $blog = $this->blogRepo->findById($id);

        if (!$blog) {
            return false;
        }

        if ($blog->thumbnail) {
            $this->uploadService->deleteFile($blog->thumbnail);
        }

        $images = $this->imageRepo->findByBlogId($id);
        foreach ($images as $image) {
            $this->uploadService->deleteFile($image->image);
        }
        $this->imageRepo->deleteByBlogId($id);

        $this->seoService->deleteByModel('blogs', $id);

        return $this->blogRepo->delete($id);
    }

    public function search(string $keyword, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $blogs = $this->blogRepo->search($keyword, $perPage, $offset);

        return [
            'data' => $blogs,
            'keyword' => $keyword
        ];
    }

    private function uploadAdditionalImages(string $blogId, array $files): void
    {
        if (!isset($files['name']) || !is_array($files['name'])) {
            return;
        }

        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                ];

                $imagePath = $this->uploadService->uploadImage($file, 'blogs');

                if ($imagePath) {
                    $image = new BlogImage([
                        'blog_id' => $blogId,
                        'image' => $imagePath,
                        'caption' => null,
                        'sort_order' => $key
                    ]);

                    $image->setUlid();
                    $this->imageRepo->create($image);
                }
            }
        }
    }

    public function deleteImage(string $imageId): bool
    {
        $image = $this->imageRepo->findById($imageId);

        if (!$image) {
            return false;
        }

        $this->uploadService->deleteFile($image->image);

        return $this->imageRepo->delete($imageId);
    }
}