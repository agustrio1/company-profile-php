<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\BlogService;
use App\Services\CompanyService;
use App\Repositories\BlogCategoryRepository;

class BlogController
{
    private BlogService $blogService;
    private CompanyService $companyService;
    private BlogCategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->blogService = new BlogService();
        $this->companyService = new CompanyService();
        $this->categoryRepo = new BlogCategoryRepository();
    }

    public function index(Request $request): Response
    {
        $page = (int)$request->query('page', 1);
        $search = $request->query('search');

        if ($search) {
            $result = $this->blogService->search($search, $page);
        } else {
            $result = $this->blogService->getPublished($page);
        }

        $company = $this->companyService->get();
        
        // SEO data untuk halaman blog index
        $seoData = [
            'title' => 'Blog & Artikel - ' . ($company->name ?? 'Konsultan Bisnis'),
            'description' => 'Baca artikel terbaru seputar tips bisnis, strategi pemasaran, dan insight dari para ahli konsultan bisnis profesional',
            'keywords' => 'blog bisnis, artikel konsultan, tips bisnis, strategi bisnis, manajemen bisnis',
            'image' => $company->getLogoUrl() ?? asset('images/logo.svg')
        ];
        
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Blog', 'url' => url('/blog')]
        ];
        
        // Get categories
        $categories = $this->categoryRepo->findAll();
        
        $data = [
            'company' => $company,
            'blogs' => $result['data'],
            'pagination' => [
                'current_page' => $result['current_page'] ?? $page,
                'last_page' => $result['last_page'] ?? 1,
                'total' => $result['total'] ?? 0,
                'per_page' => $result['per_page'] ?? 10
            ],
            'categories' => $categories,
            'search' => $search,
            'seo' => $seoData,
            'breadcrumbs' => $breadcrumbs,
            'currentUrl' => url('/blog') . ($page > 1 ? '?page=' . $page : ''),
            'canonicalUrl' => url('/blog'),
            'title' => $seoData['title']
        ];

        return Response::make()->view('pages.blog.index', $data);
    }

    public function show(Request $request, string $slug): Response
    {
        $blog = $this->blogService->getBySlug($slug);

        if (!$blog || !$blog->isPublished()) {
            return Response::make()
                ->setStatusCode(404)
                ->view('errors.404', [
                    'title' => '404 - Artikel Tidak Ditemukan',
                    'message' => 'Maaf, artikel yang Anda cari tidak ditemukan.'
                ]);
        }

        $company = $this->companyService->get();
        
        // SEO data - prioritas dari SEO settings, fallback ke blog data
        $seoTitle = null;
        $seoDescription = null;
        $seoKeywords = null;
        
        // Ambil dari SEO settings jika ada
        if (isset($blog->seo) && is_object($blog->seo)) {
            $seoTitle = $blog->seo->title ?? null;
            $seoDescription = $blog->seo->description ?? null;
            $seoKeywords = $blog->seo->keywords ?? null;
        }
        
        // Fallback jika SEO tidak ada
        if (!$seoTitle) {
            $seoTitle = $blog->title;
        }
        
        if (!$seoDescription) {
            // Ambil 160 karakter pertama dari content
            $cleanContent = strip_tags($blog->content);
            $seoDescription = mb_substr($cleanContent, 0, 160);
            if (mb_strlen($cleanContent) > 160) {
                $seoDescription .= '...';
            }
        }
        
        if (!$seoKeywords) {
            $seoKeywords = $blog->title . ', blog bisnis, artikel konsultan';
        }
        
        $seoData = [
            'title' => $seoTitle . ' - ' . ($company->name ?? 'Blog'),
            'description' => $seoDescription,
            'keywords' => $seoKeywords,
            'image' => ($blog->thumbnail ? asset($blog->thumbnail) : null) ?? $company->getLogoUrl() ?? asset('images/logo.svg'),
            'author' => $blog->author->name ?? null,
            'published_date' => $blog->created_at ?? null,
            'modified_date' => $blog->updated_at ?? null
        ];
        
        // Breadcrumbs untuk structured data
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Blog', 'url' => url('/blog')],
            ['name' => $blog->title, 'url' => url('/blog/' . $blog->slug)]
        ];
        
        // Get related blogs (same category, exclude current)
        $relatedBlogs = [];
        if ($blog->category_id) {
            $allBlogs = $this->blogService->getPublished(1, 4)['data'];
            $relatedBlogs = array_filter($allBlogs, function($b) use ($blog) {
                return $b->category_id === $blog->category_id && $b->id !== $blog->id;
            });
            $relatedBlogs = array_slice($relatedBlogs, 0, 3);
        }
        
        $data = [
            'company' => $company,
            'blog' => $blog,
            'relatedBlogs' => $relatedBlogs,
            'categories' => $this->categoryRepo->findAll(),
            'seo' => $seoData,
            'breadcrumbs' => $breadcrumbs,
            'currentUrl' => url('/blog/' . $blog->slug),
            'canonicalUrl' => url('/blog/' . $blog->slug),
            'title' => $seoData['title']
        ];

        return Response::make()->view('pages.blog.detail', $data);
    }

    public function category(Request $request, string $slug): Response
    {
        $category = $this->categoryRepo->findBySlug($slug);

        if (!$category) {
            return Response::make()
                ->setStatusCode(404)
                ->view('errors.404', [
                    'title' => '404 - Kategori Tidak Ditemukan',
                    'message' => 'Maaf, kategori yang Anda cari tidak ditemukan.'
                ]);
        }

        $company = $this->companyService->get();
        $page = (int)$request->query('page', 1);
        $result = $this->blogService->getPublished($page, 10);

        // Filter by category
        $blogs = array_filter($result['data'], fn($blog) => $blog->category_id === $category->id);
        $totalFiltered = count($blogs);
        
        // Paginate filtered results
        $blogsArray = array_values($blogs);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $paginatedBlogs = array_slice($blogsArray, $offset, $perPage);

        // SEO data untuk kategori
        $seoData = [
            'title' => $category->name . ' - Blog - ' . ($company->name ?? 'Konsultan Bisnis'),
            'description' => $category->description ?? 'Baca artikel seputar ' . $category->name . ' dari para ahli konsultan bisnis',
            'keywords' => $category->name . ', blog bisnis, artikel konsultan, tips bisnis',
            'image' => $company->getLogoUrl() ?? asset('images/logo.svg')
        ];
        
        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Blog', 'url' => url('/blog')],
            ['name' => $category->name, 'url' => url('/blog/category/' . $category->slug)]
        ];

        // Get categories
        $categories = $this->categoryRepo->findAll();

        $data = [
            'company' => $company,
            'category' => $category,
            'blogs' => $paginatedBlogs,
            'pagination' => [
                'current_page' => $page,
                'last_page' => ceil($totalFiltered / $perPage),
                'total' => $totalFiltered,
                'per_page' => $perPage
            ],
            'categories' => $categories,
            'seo' => $seoData,
            'breadcrumbs' => $breadcrumbs,
            'currentUrl' => url('/blog/category/' . $category->slug) . ($page > 1 ? '?page=' . $page : ''),
            'canonicalUrl' => url('/blog/category/' . $category->slug),
            'title' => $seoData['title']
        ];

        return Response::make()->view('pages.blog.category', $data);
    }
}