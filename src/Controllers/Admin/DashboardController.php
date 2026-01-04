<?php

namespace App\Controllers\Admin;

use App\Core\Response;
use App\Repositories\BlogRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\UserRepository;

class DashboardController
{
    private BlogRepository $blogRepo;
    private ServiceRepository $serviceRepo;
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->blogRepo = new BlogRepository();
        $this->serviceRepo = new ServiceRepository();
        $this->userRepo = new UserRepository();
    }

    public function index(): Response
    {
        $data = [
            'stats' => [
                'total_blogs' => $this->blogRepo->count(),
                'published_blogs' => $this->blogRepo->countPublished(),
                'total_services' => $this->serviceRepo->count(),
                'total_users' => $this->userRepo->count()
            ],
            'recent_blogs' => $this->blogRepo->findAll(5, 0)
        ];

        return Response::make()->view('admin.dashboard', $data);
    }
}