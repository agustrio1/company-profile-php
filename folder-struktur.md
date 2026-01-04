project-root/
├── src/
│   ├── Models/
│   │   ├── Blog.php
│   │   ├── BlogCategory.php
│   │   ├── Service.php
│   │   ├── Team.php
│   │   ├── Employee.php
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── Session.php
│   │   ├── PasswordReset.php
│   │   ├── Company.php
│   │   └── SeoMeta.php
│   ├── Services/
│   │   ├── BlogService.php
│   │   ├── ServiceService.php
│   │   ├── TeamService.php
│   │   ├── AuthService.php
│   │   ├── UserService.php
│   │   ├── RoleService.php
│   │   ├── SessionService.php
│   │   ├── PasswordResetService.php
│   │   ├── CompanyService.php
│   │   ├── UploadService.php
│   │   └── SeoService.php
│   ├── Repositories/
│   │   ├── BlogRepository.php
│   │   ├── ServiceRepository.php
│   │   ├── TeamRepository.php
│   │   ├── UserRepository.php
│   │   ├── RoleRepository.php
│   │   ├── SessionRepository.php
│   │   ├── PasswordResetRepository.php
│   │   ├── CompanyRepository.php
│   │   └── SeoRepository.php
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── AboutController.php
│   │   ├── BlogController.php
│   │   ├── ServiceController.php
│   │   ├── TeamController.php
│   │   ├── ContactController.php
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── BlogController.php
│   │       ├── ServiceController.php
│   │       ├── TeamController.php
│   │       ├── CompanyController.php
│   │       ├── UserController.php
│   │       └── RoleController.php
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── GuestMiddleware.php
│   │   ├── RoleMiddleware.php
│   │   └── ValidationMiddleware.php
│   ├── Validators/
│   │   ├── BlogValidator.php
│   │   ├── ServiceValidator.php
│   │   ├── TeamValidator.php
│   │   ├── AuthValidator.php
│   │   └── UserValidator.php
│   ├── Core/
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Session.php
│   │   ├── View.php
│   │   └── Helpers.php
│   ├── Config/
│   │   ├── database.php
│   │   ├── app.php
│   │   └── paths.php
│   └── Traits/
│       ├── HasUlid.php
│       └── HasTimestamps.php
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── build/
│   │   └── manifest.json
│   │   └── assets 
│   │        └── css
│   │        └── js
│   ├── images/
│   └── uploads/
│       ├── blogs/
│       ├── services/
│       └── team/
├── resources/
│   └──  css
│       ├── app.css  -> tailwind css
│   └──  js
│       ├── app.js ->  alpine.js
│   └── views/
│       ├── layouts/
│       │   ├── app.phps
│       │   ├── admin.php
│       │   └── guest.php
│       ├── components/
│       │   ├── header.php
│       │   ├── footer.php
│       │   ├── navbar.php
│       │   └── sidebar.php
│       ├── pages/
│       │   ├── home.php
│       │   ├── about.php
│       │   ├── contact.php
│       │   ├── services/
│       │   │   ├── index.php
│       │   │   └── detail.php
│       │   ├── team/
│       │   │   └── index.php
│       │   └── blog/
│       │       ├── index.php
│       │       ├── category.php
│       │       └── detail.php
│       ├── admin/
│       │   ├── dashboard.php
│       │   ├── blogs/
│       │   │   ├── index.php
│       │   │   ├── create.php
│       │   │   └── edit.php
│       │   ├── services/
│       │   │   ├── index.php
│       │   │   ├── create.php
│       │   │   └── edit.php
│       │   ├── team/
│       │   │   ├── index.php
│       │   │   ├── create.php
│       │   │   └── edit.php
│       │   ├── company/
│       │   │   └── edit.php
│       │   ├── users/
│       │   │   ├── index.php
│       │   │   ├── create.php
│       │   │   └── edit.php
│       │   └── roles/
│       │       ├── index.php
│       │       ├── create.php
│       │       └── edit.php
│       └── auth/
│           ├── login.php
│           ├── register.php
│           ├── forgot-password.php
│           └── reset-password.php
├── routes/
│   ├── web.php
│   ├── admin.php
│   └── api.php
├── database/
│   ├── migrations/
│   │   └── 2025_01_01_000000_create_initial_schema.sql
│   └── seeds/
│       ├── RoleSeeder.php
│       └── UserSeeder.php
├── storage/
│   ├── logs/
│   └── cache/
├── vendor/
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
└── README.md

composer dump-autoload


Flow request yang BENAR:
public/index.php 
  → Router 
    → Middleware 
      → Controller 
        → Service 
          → Repository 
            → Model 
              → Database
Penjelasan setiap layer:
index.php - Entry point aplikasi
Router - Match URL ke Controller
Middleware - Auth, validation, RBAC check
Controller - Terima request, panggil Service
Service - Business logic, orchestration
Repository - Raw query SQL (interaction dengan DB)
Model - Representasi data, mapping result query
Database - PostgreSQL connection
Contoh flow konkrit:
GET /admin/blogs
  → Router (match route)
  → AuthMiddleware (cek login)
  → RoleMiddleware (cek permission)
  → BlogController->index()
    → BlogService->getAllBlogs()
      → BlogRepository->findAll() (raw SQL query)
        → Model Blog (mapping data)
          → Database (execute query)
  