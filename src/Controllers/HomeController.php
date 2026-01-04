<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\CompanyService;
use App\Services\CompanyServiceService;
use App\Services\BlogService;
use App\Services\TeamService;

class HomeController
{
    private CompanyService $companyService;
    private CompanyServiceService $serviceService;
    private BlogService $blogService;
    private TeamService $teamService;

    public function __construct()
    {
        $this->companyService = new CompanyService();
        $this->serviceService = new CompanyServiceService();
        $this->blogService = new BlogService();
        $this->teamService = new TeamService();
    }

    public function index(Request $request): Response
    {
        $company = $this->companyService->get();
        $services = $this->serviceService->getFeatured();
        $blogsData = $this->blogService->getPublished(1, 3);
        
        // Get teams with employees - ambil array data langsung
        $teamsData = $this->teamService->getAllWithEmployees(1, 50);
        $teams = $teamsData['data']; // Ambil array data saja

        // SEO data untuk homepage
        $seoData = [
            'title' => ($company->seo->title ?? null) ?: ($company->name ?? 'Home') . ' - Layanan Sewa Bus Pariwisata Terpercaya',
            'description' => ($company->seo->description ?? null) ?: ($company->description ?? 'Layanan sewa bus pariwisata dengan armada terawat, driver profesional, dan harga kompetitif'),
            'keywords' => ($company->seo->keywords ?? null) ?: 'sewa bus, charter bus, bus pariwisata, rental bus, bus wisata',
            'image' => $company->getLogoUrl() ?? asset('images/logo.svg')
        ];

        $data = [
            'company' => $company,
            'services' => $services,
            'blogs' => $blogsData['data'],
            'teams' => $teams, // Ini sudah array of Team objects dengan property employees
            'seo' => $seoData,
            'currentUrl' => url('/'),
            'canonicalUrl' => url('/'),
            'title' => $seoData['title']
        ];

        return Response::make()->view('pages.home', $data);
    }

    public function about(Request $request): Response
    {
        $company = $this->companyService->get();
        
        // Get teams with employees
        $teamsData = $this->teamService->getAllWithEmployees(1, 50);
        $teams = $teamsData['data'];

        // SEO data untuk about page
        $seoData = [
            'title' => 'Tentang Kami - ' . ($company->name ?? 'Charter Bus'),
            'description' => $company->description ?? 'Pelajari lebih lanjut tentang layanan sewa bus kami, visi, dan misi perusahaan',
            'keywords' => 'tentang kami, profil perusahaan, visi misi, charter bus',
            'image' => $company->getLogoUrl() ?? asset('images/logo.svg')
        ];

        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Tentang Kami', 'url' => url('/about')]
        ];

        $data = [
            'company' => $company,
            'teams' => $teams,
            'seo' => $seoData,
            'breadcrumbs' => $breadcrumbs,
            'currentUrl' => url('/about'),
            'canonicalUrl' => url('/about'),
            'title' => $seoData['title']
        ];

        return Response::make()->view('pages.about', $data);
    }

    public function contact(Request $request): Response
    {
        $company = $this->companyService->get();

        // SEO data untuk contact page
        $seoData = [
            'title' => 'Hubungi Kami - ' . ($company->name ?? 'Charter Bus'),
            'description' => 'Hubungi kami untuk reservasi bus atau konsultasi layanan. Tim kami siap membantu Anda 24/7',
            'keywords' => 'hubungi kami, kontak, reservasi, booking bus, customer service',
            'image' => $company->getLogoUrl() ?? asset('images/logo.svg')
        ];

        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Hubungi Kami', 'url' => url('/contact')]
        ];

        $data = [
            'company' => $company,
            'seo' => $seoData,
            'breadcrumbs' => $breadcrumbs,
            'currentUrl' => url('/contact'),
            'canonicalUrl' => url('/contact'),
            'title' => $seoData['title']
        ];

        return Response::make()->view('pages.contact', $data);
    }
}