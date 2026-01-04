<?php

use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\BlogController;
use App\Controllers\Admin\BlogCategoryController;
use App\Controllers\Admin\ServiceController;
use App\Controllers\Admin\TeamController;
use App\Controllers\Admin\CompanyController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\RoleController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Middleware\CsrfMiddleware;

// All admin routes require authentication
$router->group(['middleware' => [AuthMiddleware::class]], function ($router) {
    
    // Dashboard
    $router->get('/admin/dashboard', [DashboardController::class, 'index']);

    // Blog Management (dengan HTMX endpoints)
    $router->get('/admin/blogs', [BlogController::class, 'index']);
    $router->get('/admin/blogs/table', [BlogController::class, 'table']);
    $router->get('/admin/blogs/create', [BlogController::class, 'create']);
    $router->post('/admin/blogs', [BlogController::class, 'store'])
        ->middleware(CsrfMiddleware::class);
    $router->get('/admin/blogs/{id}/edit', [BlogController::class, 'edit']);
    $router->get('/admin/blogs/{id}/confirm-delete', [BlogController::class, 'confirmDelete']);
    $router->put('/admin/blogs/{id}', [BlogController::class, 'update'])
        ->middleware(CsrfMiddleware::class);
    $router->delete('/admin/blogs/{id}', [BlogController::class, 'destroy'])
        ->middleware(CsrfMiddleware::class);
    $router->post('/admin/blogs/{id}/publish', [BlogController::class, 'publish'])
        ->middleware(CsrfMiddleware::class);
    $router->post('/admin/blogs/{id}/unpublish', [BlogController::class, 'unpublish'])
        ->middleware(CsrfMiddleware::class);

    // Blog Category Management (dengan HTMX endpoints)
    $router->get('/admin/blog-categories', [BlogCategoryController::class, 'index']);
    $router->get('/admin/blog-categories/table', [BlogCategoryController::class, 'table']);
    $router->get('/admin/blog-categories/create', [BlogCategoryController::class, 'create']);
    $router->post('/admin/blog-categories', [BlogCategoryController::class, 'store'])
        ->middleware(CsrfMiddleware::class);
    $router->get('/admin/blog-categories/{id}/edit', [BlogCategoryController::class, 'edit']);
    $router->get('/admin/blog-categories/{id}/confirm-delete', [BlogCategoryController::class, 'confirmDelete']);
    $router->put('/admin/blog-categories/{id}', [BlogCategoryController::class, 'update'])
        ->middleware(CsrfMiddleware::class);
    $router->delete('/admin/blog-categories/{id}', [BlogCategoryController::class, 'destroy'])
        ->middleware(CsrfMiddleware::class);

    // Service Management (dengan HTMX endpoints)
    $router->get('/admin/services', [ServiceController::class, 'index']);
    $router->get('/admin/services/table', [ServiceController::class, 'table']);
    $router->get('/admin/services/create', [ServiceController::class, 'create']);
    $router->post('/admin/services', [ServiceController::class, 'store'])
        ->middleware(CsrfMiddleware::class);
    $router->get('/admin/services/{id}/edit', [ServiceController::class, 'edit']);
    $router->get('/admin/services/{id}/confirm-delete', [ServiceController::class, 'confirmDelete']);
    $router->put('/admin/services/{id}', [ServiceController::class, 'update'])
        ->middleware(CsrfMiddleware::class);
    $router->delete('/admin/services/{id}', [ServiceController::class, 'destroy'])
        ->middleware(CsrfMiddleware::class);

    // Team Management (dengan HTMX endpoints)
    $router->get('/admin/teams', [TeamController::class, 'index']);
    $router->get('/admin/teams/table', [TeamController::class, 'table']);
    $router->get('/admin/teams/create', [TeamController::class, 'create']);
    $router->post('/admin/teams', [TeamController::class, 'store'])
        ->middleware(CsrfMiddleware::class);
    $router->get('/admin/teams/{id}/edit', [TeamController::class, 'edit']);
    $router->get('/admin/teams/{id}/confirm-delete', [TeamController::class, 'confirmDelete']);
    $router->put('/admin/teams/{id}', [TeamController::class, 'update'])
        ->middleware(CsrfMiddleware::class);
    $router->delete('/admin/teams/{id}', [TeamController::class, 'destroy'])
        ->middleware(CsrfMiddleware::class);

    // Employee Management (dengan HTMX endpoints)
    $router->get('/admin/teams/{teamId}/employees/create', [TeamController::class, 'createEmployee']);
    $router->post('/admin/employees', [TeamController::class, 'storeEmployee'])
        ->middleware(CsrfMiddleware::class);
    $router->get('/admin/employees/{id}/edit', [TeamController::class, 'editEmployee']);
    $router->get('/admin/employees/{id}/confirm-delete', [TeamController::class, 'confirmDeleteEmployee']);
    $router->put('/admin/employees/{id}', [TeamController::class, 'updateEmployee'])
        ->middleware(CsrfMiddleware::class);
    $router->delete('/admin/employees/{id}', [TeamController::class, 'destroyEmployee'])
        ->middleware(CsrfMiddleware::class);

    // Company Profile Management
    $router->get('/admin/company/edit', [CompanyController::class, 'edit']);
    $router->put('/admin/company', [CompanyController::class, 'update'])
        ->middleware(CsrfMiddleware::class);

    // User Management (dengan HTMX endpoints)
    $router->get('/admin/users', [UserController::class, 'index'])
        ->middleware([new RoleMiddleware(['manage_users'])]);
    $router->get('/admin/users/table', [UserController::class, 'table'])
        ->middleware([new RoleMiddleware(['manage_users'])]);
    $router->get('/admin/users/create', [UserController::class, 'create'])
        ->middleware([new RoleMiddleware(['manage_users'])]);
    $router->post('/admin/users', [UserController::class, 'store'])
        ->middleware([CsrfMiddleware::class, new RoleMiddleware(['manage_users'])]);
    $router->get('/admin/users/{id}/edit', [UserController::class, 'edit'])
        ->middleware([new RoleMiddleware(['manage_users'])]);
    $router->get('/admin/users/{id}/confirm-delete', [UserController::class, 'confirmDelete'])
        ->middleware([new RoleMiddleware(['manage_users'])]);
    $router->put('/admin/users/{id}', [UserController::class, 'update'])
        ->middleware([CsrfMiddleware::class, new RoleMiddleware(['manage_users'])]);
    $router->delete('/admin/users/{id}', [UserController::class, 'destroy'])
        ->middleware([CsrfMiddleware::class, new RoleMiddleware(['manage_users'])]);
        
    // Role Management
    $router->get('/admin/roles', [RoleController::class, 'index'])
        ->middleware([new RoleMiddleware(['manage_roles'])]);
    $router->get('/admin/roles/create', [RoleController::class, 'create'])
        ->middleware([new RoleMiddleware(['manage_roles'])]);
    $router->post('/admin/roles', [RoleController::class, 'store'])
        ->middleware([CsrfMiddleware::class, new RoleMiddleware(['manage_roles'])]);
    $router->get('/admin/roles/{id}/edit', [RoleController::class, 'edit'])
        ->middleware([new RoleMiddleware(['manage_roles'])]);
    $router->put('/admin/roles/{id}', [RoleController::class, 'update'])
        ->middleware([CsrfMiddleware::class, new RoleMiddleware(['manage_roles'])]);
    $router->delete('/admin/roles/{id}', [RoleController::class, 'destroy'])
        ->middleware([CsrfMiddleware::class, new RoleMiddleware(['manage_roles'])]);
});