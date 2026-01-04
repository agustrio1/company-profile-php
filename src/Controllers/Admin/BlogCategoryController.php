<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\BlogCategoryRepository;
use App\Models\BlogCategory;
use App\Validators\BlogValidator;

class BlogCategoryController
{
    private BlogCategoryRepository $categoryRepo;
    private BlogValidator $validator;

    public function __construct()
    {
        $this->categoryRepo = new BlogCategoryRepository();
        $this->validator = new BlogValidator();
    }

    public function index(Request $request): Response
    {
        return Response::make()->view('admin.blog-categories.index');
    }

    public function table(Request $request): Response
    {
        $search = $request->query('search');
        
        $categories = $search 
            ? $this->categoryRepo->search($search)
            : $this->categoryRepo->findAll();
        
        return Response::make()->view('admin.blog-categories._table', [
            'categories' => $categories,
            'search' => $search
        ], false);
    }

    public function create(Request $request): Response
    {
        return Response::make()->view('admin.blog-categories.create');
    }

    public function store(Request $request): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        if (!$this->validator->validateCategory($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $category = new BlogCategory([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? slugify($data['name']),
            'description' => $data['description'] ?? null
        ]);

        $category->setUlid();

        if (!$this->categoryRepo->create($category)) {
            return Response::make()
                ->with('error', 'Failed to create category')
                ->back();
        }

        return Response::make()
            ->with('success', 'Category created successfully')
            ->redirect(url('admin/blog-categories'));
    }

    public function edit(Request $request, string $id): Response
    {
        $category = $this->categoryRepo->findById($id);

        if (!$category) {
            return Response::make()
                ->setStatusCode(404)
                ->setContent('Category not found');
        }

        return Response::make()->view('admin.blog-categories.edit', ['category' => $category]);
    }

    public function update(Request $request, string $id): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        if (!$this->validator->validateCategory($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $category = $this->categoryRepo->findById($id);

        if (!$category) {
            return Response::make()
                ->setStatusCode(404)
                ->setContent('Category not found');
        }

        $category->name = $data['name'];
        $category->slug = $data['slug'] ?? slugify($data['name']);
        $category->description = $data['description'] ?? null;

        if (!$this->categoryRepo->update($category)) {
            return Response::make()
                ->with('error', 'Failed to update category')
                ->back();
        }

        return Response::make()
            ->with('success', 'Category updated successfully')
            ->redirect(url('admin/blog-categories'));
    }

    public function confirmDelete(Request $request, string $id): Response
    {
        $category = $this->categoryRepo->findById($id);
        
        if (!$category) {
            return Response::make()
                ->setStatusCode(404)
                ->setContent('Kategori tidak ditemukan');
        }
        
        // Cek apakah category punya blogs
        $blogCount = $this->categoryRepo->getBlogCount($id);
        
        return Response::make()->view('admin.blog-categories._delete_modal', [
            'category' => $category,
            'blogCount' => $blogCount
        ], false);
    }

    public function destroy(Request $request, string $id): Response
    {
        $isAjax = $request->header('Accept') === 'application/json' || 
                  $request->header('X-Requested-With') === 'XMLHttpRequest';
        $isHtmx = $request->header('HX-Request') !== null;
        
        // Cek apakah category punya blogs
        $blogCount = $this->categoryRepo->getBlogCount($id);
        
        if ($blogCount > 0) {
            $message = "Kategori tidak dapat dihapus karena masih memiliki {$blogCount} blog";
            
            if ($isHtmx) {
                return Response::make()
                    ->setStatusCode(400)
                    ->setContent($this->htmxScript($message, 'danger'));
            }
            
            if ($isAjax) {
                return Response::make()
                    ->json(['success' => false, 'message' => $message], 400);
            }
            
            return Response::make()->with('error', $message)->back();
        }
        
        $deleted = $this->categoryRepo->delete($id);
        
        // HTMX response
        if ($isHtmx) {
            if (!$deleted) {
                return Response::make()
                    ->setStatusCode(400)
                    ->setContent($this->htmxScript('Gagal menghapus kategori', 'danger'));
            }
            
            header('HX-Trigger: categoryDeleted');
            return Response::make()->setContent($this->htmxScript('Kategori berhasil dihapus', 'success'));
        }
        
        // AJAX response
        if ($isAjax) {
            return Response::make()->json([
                'success' => $deleted,
                'message' => $deleted ? 'Kategori berhasil dihapus' : 'Gagal menghapus kategori'
            ], $deleted ? 200 : 500);
        }
        
        // Form redirect
        return Response::make()
            ->with($deleted ? 'success' : 'error', $deleted ? 'Category deleted successfully' : 'Failed to delete category')
            ->redirect($deleted ? url('admin/blog-categories') : null)
            ->back();
    }
    
    private function htmxScript(string $message, string $type): string
    {
        return '<script>
            document.getElementById("delete-modal").innerHTML = "";
            showToast("' . addslashes($message) . '", "' . $type . '");
        </script>';
    }
}