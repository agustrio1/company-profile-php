<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\CompanyService;
use App\Services\CompanyServiceService;

class ServiceController
{
    private CompanyServiceService $serviceService;
    private CompanyService $companyService;

    public function __construct()
    {
        $this->serviceService = new CompanyServiceService();
        $this->companyService = new CompanyService();
    }

    public function index(Request $request): Response
    {
        $company = $this->companyService->get();
        $servicesData = $this->serviceService->getAll();

        // SEO data untuk services page
        $seoData = [
            'title' => 'Layanan Konsultan Bisnis - ' . ($company->name ?? 'Konsultan Profesional'),
            'description' => 'Layanan konsultasi bisnis profesional untuk membantu mengembangkan dan mengoptimalkan strategi bisnis Anda dengan pendekatan yang terukur',
            'keywords' => 'konsultan bisnis, konsultasi strategi bisnis, manajemen bisnis, business consulting, business strategy',
            'image' => $company->getLogoUrl() ?? asset('images/logo.svg')
        ];

        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Layanan', 'url' => url('/services')]
        ];

        $data = [
            'company' => $company,
            'services' => $servicesData['data'],
            'seo' => $seoData,
            'breadcrumbs' => $breadcrumbs,
            'currentUrl' => url('/services'),
            'canonicalUrl' => url('/services'),
            'title' => $seoData['title']
        ];

        return Response::make()->view('pages.services.index', $data);
    }

    public function show(Request $request, string $slug): Response
    {
        $company = $this->companyService->get();
        $service = $this->serviceService->getBySlug($slug);

        if (!$service) {
            return Response::make()
                ->setStatusCode(404)
                ->view('errors.404', [
                    'title' => '404 - Layanan Tidak Ditemukan',
                    'message' => 'Maaf, layanan yang Anda cari tidak ditemukan.'
                ]);
        }

        // SEO data untuk service detail page
        $seoTitle = $service->seo->title ?? $service->title . ' - Konsultan Bisnis';
        $seoDescription = $service->seo->description ?? ($service->description ?? 'Layanan konsultasi ' . $service->title . ' untuk membantu mengembangkan bisnis Anda');
        $seoKeywords = $service->seo->keywords ?? ($service->title . ', konsultan bisnis, konsultasi bisnis, strategi bisnis');

        $seoData = [
            'title' => $seoTitle . ' - ' . ($company->name ?? ''),
            'description' => $seoDescription,
            'keywords' => $seoKeywords,
            'image' => ($service->image ? asset($service->image) : null) ?? $company->getLogoUrl() ?? asset('images/logo.svg')
        ];

        $breadcrumbs = [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Layanan', 'url' => url('/services')],
            ['name' => $service->title, 'url' => url('/services/' . $service->slug)]
        ];

        // Get related services (exclude current service)
        $allServicesData = $this->serviceService->getFeatured();
        $relatedServices = array_filter($allServicesData['data'], function($s) use ($service) {
            return $s->id !== $service->id;
        });
        $relatedServices = array_slice($relatedServices, 0, 3);

        $data = [
            'company' => $company,
            'service' => $service,
            'relatedServices' => $relatedServices,
            'seo' => $seoData,
            'breadcrumbs' => $breadcrumbs,
            'currentUrl' => url('/services/' . $service->slug),
            'canonicalUrl' => url('/services/' . $service->slug),
            'title' => $seoData['title']
        ];

        return Response::make()->view('pages.services.detail', $data);
    }
}