<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Log;
use App\Services\BlogService;
use App\Repositories\BlogCategoryRepository;
use App\Validators\BlogValidator;

class BlogController
{
    private BlogService $blogService;
    private BlogCategoryRepository $categoryRepo;
    private BlogValidator $validator;

    public function __construct()
    {
        $this->blogService = new BlogService();
        $this->categoryRepo = new BlogCategoryRepository();
        $this->validator = new BlogValidator();
    }

    public function index(Request $request): Response
    {
        return Response::make()->view('admin.blogs.index');
    }

    public function table(Request $request): Response
    {
        $search = $request->query('search');
        $page = (int)$request->query('page', 1);
        
        $result = $search 
            ? $this->blogService->search($search, $page, 50)
            : $this->blogService->getAll($page, 50);
        
        return Response::make()->view('admin.blogs._table', [
            'blogs' => $result['data'],
            'search' => $search
        ], false);
    }

    public function create(Request $request): Response
    {
        return Response::make()->view('admin.blogs.create', [
            'categories' => $this->categoryRepo->findAll()
        ]);
    }

    public function store(Request $request): Response
    {
        $data = $request->except(['_csrf_token', '_method']);
        $data['author_id'] = $_SESSION['auth_user_id'];

        Log::info('Creating new blog', [
            'title' => $data['title'] ?? null,
            'seo' => $data['seo'] ?? null
        ]);

        if (!$this->validator->validateCreate($data)) {
            Log::warning('Blog validation failed', [
                'errors' => $this->validator->getErrors()
            ]);
            
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $thumbnail = $request->file('thumbnail');
        $additionalImages = $request->file('additional_images');

        $blog = $this->blogService->create($data, $thumbnail, $additionalImages);

        if (!$blog) {
            Log::error('Failed to create blog');
            
            return Response::make()
                ->with('error', 'Failed to create blog')
                ->back();
        }

        Log::info('Blog created successfully', ['blog_id' => $blog->id]);

        return Response::make()
            ->with('success', 'Blog created successfully')
            ->redirect(url('admin/blogs'));
    }

    public function edit(Request $request, string $id): Response
    {
        $blog = $this->blogService->getById($id);

        if (!$blog) {
            Log::warning('Blog not found for edit', ['id' => $id]);
            
            return Response::make()
                ->setStatusCode(404)
                ->setContent('Blog not found');
        }

        return Response::make()->view('admin.blogs.edit', [
            'blog' => $blog,
            'categories' => $this->categoryRepo->findAll()
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        Log::info('Updating blog', [
            'id' => $id,
            'seo' => $data['seo'] ?? null
        ]);

        if (!$this->validator->validateUpdate($data)) {
            Log::warning('Blog update validation failed', [
                'id' => $id,
                'errors' => $this->validator->getErrors()
            ]);
            
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $thumbnail = $request->file('thumbnail');
        $additionalImages = $request->file('additional_images');

        $blog = $this->blogService->update($id, $data, $thumbnail, $additionalImages);

        if (!$blog) {
            Log::error('Failed to update blog', ['id' => $id]);
            
            return Response::make()
                ->with('error', 'Failed to update blog')
                ->back();
        }

        Log::info('Blog updated successfully', ['id' => $id]);

        return Response::make()
            ->with('success', 'Blog updated successfully')
            ->redirect(url('admin/blogs'));
    }

    public function confirmDelete(Request $request, string $id): Response
    {
        $blog = $this->blogService->getById($id);
        
        if (!$blog) {
            return Response::make()
                ->setStatusCode(404)
                ->setContent('Blog tidak ditemukan');
        }
        
        return Response::make()->view('admin.blogs._delete_modal', ['blog' => $blog], false);
    }

    public function destroy(Request $request, string $id): Response
    {
        $isAjax = $request->header('Accept') === 'application/json' || 
                  $request->header('X-Requested-With') === 'XMLHttpRequest';
        $isHtmx = $request->header('HX-Request') !== null;
        
        $deleted = $this->blogService->delete($id);
        
        if ($deleted) {
            Log::info('Blog deleted', ['id' => $id]);
        } else {
            Log::error('Failed to delete blog', ['id' => $id]);
        }
        
        if ($isHtmx) {
            if (!$deleted) {
                return Response::make()
                    ->setStatusCode(400)
                    ->setContent($this->htmxScript('Gagal menghapus blog', 'danger'));
            }
            
            header('HX-Trigger: blogDeleted');
            return Response::make()->setContent($this->htmxScript('Blog berhasil dihapus', 'success'));
        }
        
        if ($isAjax) {
            return Response::make()->json([
                'success' => $deleted,
                'message' => $deleted ? 'Blog berhasil dihapus' : 'Gagal menghapus blog'
            ], $deleted ? 200 : 500);
        }
        
        return Response::make()
            ->with($deleted ? 'success' : 'error', $deleted ? 'Blog deleted successfully' : 'Failed to delete blog')
            ->redirect($deleted ? url('admin/blogs') : null)
            ->back();
    }

    public function publish(Request $request, string $id): Response
    {
        $isHtmx = $request->header('HX-Request') !== null;
        
        $success = $this->blogService->publish($id);
        
        if ($success) {
            Log::info('Blog published', ['id' => $id]);
        }
        
        if ($isHtmx) {
            if (!$success) {
                return Response::make()
                    ->setStatusCode(400)
                    ->setContent($this->htmxScript('Gagal mempublikasi blog', 'danger'));
            }
            
            header('HX-Trigger: blogPublished');
            return Response::make()->setContent($this->htmxScript('Blog berhasil dipublikasi', 'success'));
        }
        
        return Response::make()->json([
            'success' => $success,
            'message' => $success ? 'Blog published successfully' : 'Failed to publish blog'
        ], $success ? 200 : 500);
    }

    public function unpublish(Request $request, string $id): Response
    {
        $isHtmx = $request->header('HX-Request') !== null;
        
        $success = $this->blogService->unpublish($id);
        
        if ($success) {
            Log::info('Blog unpublished', ['id' => $id]);
        }
        
        if ($isHtmx) {
            if (!$success) {
                return Response::make()
                    ->setStatusCode(400)
                    ->setContent($this->htmxScript('Gagal unpublish blog', 'danger'));
            }
            
            header('HX-Trigger: blogUnpublished');
            return Response::make()->setContent($this->htmxScript('Blog berhasil di-unpublish', 'success'));
        }
        
        return Response::make()->json([
            'success' => $success,
            'message' => $success ? 'Blog unpublished successfully' : 'Failed to unpublish blog'
        ], $success ? 200 : 500);
    }
    
    private function htmxScript(string $message, string $type): string
    {
        return '<script>
            document.getElementById("delete-modal").innerHTML = "";
            showToast("' . addslashes($message) . '", "' . $type . '");
        </script>';
    }
}