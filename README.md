# 🏢 Company Profile - PHP Native

> Modern, secure, and fast company profile website built with pure PHP and PostgreSQL

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-14%2B-4169E1?logo=postgresql&logoColor=white)](https://www.postgresql.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A professional company profile CMS with modern architecture, built without frameworks. Perfect for agencies, startups, and businesses looking for a lightweight yet powerful solution.

## ✨ Key Features

### 🎯 Core Features
- **Blog System** - Full-featured blog with categories, tags, and SEO optimization
- **Services Showcase** - Display your company services/products beautifully
- **Team Management** - Manage team members and organizational structure
- **Company Profile** - Dynamic company information management
- **Contact Forms** - Professional contact form with email notifications
- **SEO Optimized** - Meta tags, Open Graph, Twitter Cards support

### 🔐 Security & Authentication
- **RBAC System** - Role-Based Access Control (Super Admin, Admin, Editor)
- **CSRF Protection** - Token-based protection for all forms
- **Password Security** - bcrypt hashing with salt
- **Session Management** - Secure session handling with HttpOnly cookies
- **SQL Injection Prevention** - Prepared statements for all queries
- **XSS Protection** - Output escaping and input sanitization

### 🎨 Modern Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **Flowbite Components** - Beautiful UI components
- **Alpine.js** - Lightweight JavaScript framework
- **Responsive Design** - Mobile-first approach

### 🏗️ Architecture
- **MVC Pattern** - Clean separation of concerns
- **Repository Pattern** - Data access layer abstraction
- **Service Layer** - Business logic encapsulation
- **ULID Primary Keys** - Universally Unique Lexicographically Sortable Identifiers
- **Raw SQL** - No ORM overhead, pure performance

### 📧 Email Integration
- **Resend API** - Modern transactional email service
- **Password Reset** - Secure forgot password flow
- **Email Templates** - Professional HTML email templates
- **Contact Notifications** - Instant email alerts

### 🖼️ Media Management
- **Auto WebP Conversion** - Automatic image optimization
- **Image Resizing** - Multiple sizes for responsive images
- **File Upload** - Secure file upload with validation
- **Storage Management** - Organized file structure

## 📋 Requirements

- **PHP** >= 8.2
- **PostgreSQL** >= 14 (NeonDB recommended for cloud)
- **Composer** - Dependency management
- **Node.js** >= 18 (for asset building)
- **PHP Extensions:**
  - `pdo_pgsql` - PostgreSQL driver
  - `gd` or `imagick` - Image processing
  - `mbstring` - Multibyte string support
  - `curl` - HTTP requests
  - `json` - JSON processing

## 🚀 Quick Start

### 1️⃣ Clone & Install

```bash
# Clone repository
git clone https://github.com/agustrio1/company-profile-php.git
cd company-profile-php

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build assets
npm run build
```

### 2️⃣ Environment Setup

```bash
# Copy environment file
cp .env.example .env
```

Edit `.env` with your credentials:

```env
# Application
APP_NAME="My Company"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (NeonDB)
DB_CONNECTION=pgsql
DB_HOST=your-project.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Email (Resend)
RESEND_API_KEY=re_your_api_key
MAIL_FROM_ADDRESS=noreply@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"

# Session
SESSION_LIFETIME=120
SESSION_SECURE=false
SESSION_HTTP_ONLY=true

# Upload
MAX_UPLOAD_SIZE=10485760
ALLOWED_IMAGE_TYPES=jpg,jpeg,png,gif,webp
```

### 3️⃣ Database Setup

```bash
# Run migrations and seed data
composer migrate:fresh-seed
```

This will create:
- ✅ All database tables
- ✅ Default roles (Super Admin, Admin, Editor)
- ✅ Permissions system
- ✅ Demo admin user
- ✅ Sample content (optional)

### 4️⃣ Start Server

```bash
# Development server
composer serve

# Or using PHP built-in server
php -S localhost:8000 -t public
```

Visit: **http://localhost:8000**

### 5️⃣ Login

```
Email: admin@gmail.com
Password: password123
```

> ⚠️ **Important:** Change the default password immediately after first login!

## 📦 Available Commands

### 🗄️ Database Commands

```bash
composer migrate              # Run pending migrations
composer migrate:fresh        # Drop all tables & re-migrate
composer migrate:fresh-seed   # Fresh migration + seed data
composer migrate:status       # Check migration status
composer migrate:rollback     # Rollback last migration
composer db:seed              # Run seeders only
```

### 🔧 Development Commands

```bash
composer serve                # Start development server (port 8000)
composer dump-autoload        # Regenerate autoload files
composer test                 # Run PHPUnit tests (coming soon)
```

### 🎨 Asset Commands

```bash
npm run dev                   # Development build with watch
npm run build                 # Production build
npm run watch                 # Watch for changes
```

## 📁 Project Structure

```
company-profile-php/
│
├── 📂 src/                    # Application source code
│   ├── Config/               # Configuration files
│   ├── Controllers/          # Request handlers
│   │   ├── Admin/           # Admin panel controllers
│   │   └── Auth/            # Authentication controllers
│   ├── Core/                # Core system classes
│   │   ├── Database.php     # Database connection
│   │   ├── Router.php       # Request routing
│   │   └── View.php         # Template rendering
│   ├── Middleware/          # HTTP middleware
│   │   ├── AuthMiddleware.php
│   │   ├── GuestMiddleware.php
│   │   └── RoleMiddleware.php
│   ├── Models/              # Data models
│   ├── Repositories/        # Database queries (raw SQL)
│   ├── Services/            # Business logic
│   ├── Validators/          # Input validation
│   └── Traits/              # Reusable traits
│
├── 📂 public/                # Public web root
│   ├── index.php            # Application entry point
│   ├── .htaccess            # Apache configuration
│   ├── build/               # Compiled assets
│   ├── images/              # Static images
│   └── uploads/             # User uploads
│
├── 📂 resources/             # Frontend resources
│   ├── css/                 # Stylesheets
│   │   └── app.css          # Tailwind CSS entry
│   ├── js/                  # JavaScript
│   │   └── app.js           # Alpine.js entry
│   └── views/               # PHP templates
│       ├── layouts/         # Layout templates
│       ├── pages/           # Public pages
│       ├── admin/           # Admin pages
│       └── auth/            # Auth pages
│
├── 📂 routes/                # Route definitions
│   ├── web.php              # Public routes
│   ├── admin.php            # Admin routes
│   └── api.php              # API routes
│
├── 📂 database/              # Database files
│   ├── migrations/          # SQL migrations
│   └── seeds/               # Database seeders
│
├── 📂 storage/               # Application storage
│   ├── logs/                # Log files
│   └── cache/               # Cache files
│
├── 📜 .env.example           # Environment template
├── 📜 composer.json          # PHP dependencies
├── 📜 package.json           # Node dependencies
├── 📜 tailwind.config.js     # Tailwind configuration
└── 📜 vite.config.js         # Vite configuration
```

## 🔄 Request Flow

```
Browser Request
    ↓
public/index.php (Entry Point)
    ↓
Router (Match URL to Controller)
    ↓
Middleware Chain
    ├── AuthMiddleware (Check login)
    ├── RoleMiddleware (Check permissions)
    └── CSRFMiddleware (Validate token)
    ↓
Controller (Handle request)
    ↓
Service Layer (Business logic)
    ↓
Repository Layer (Database queries)
    ↓
Model (Data mapping)
    ↓
Database (PostgreSQL)
    ↓
Response (HTML/JSON)
```

## 🗃️ Database Schema

### Core Tables
- `migrations` - Migration tracking
- `company` - Company information
- `seo_meta` - SEO metadata (polymorphic)

### User Management
- `users` - System users
- `roles` - User roles
- `permissions` - System permissions
- `role_permissions` - Role-permission mapping
- `sessions` - Active user sessions
- `password_resets` - Password reset tokens

### Content Management
- `blogs` - Blog posts
- `blog_categories` - Blog categories
- `blog_images` - Additional blog images
- `services` - Company services/products
- `teams` - Organizational teams
- `employees` - Team members

## 👥 Role & Permissions

### Super Admin 🔴
- Full system access
- User management
- Role management
- System configuration

### Admin 🟡
- Content management (blogs, services, team)
- View users
- Edit company profile
- Cannot modify roles/permissions

### Editor 🟢
- Create/edit blogs
- Manage services
- Cannot access user management
- Cannot edit company profile

## 🔒 Security Features

✅ **Input Validation** - Server-side validation for all inputs  
✅ **CSRF Protection** - Token-based protection for state-changing requests  
✅ **Password Hashing** - bcrypt with automatic salt generation  
✅ **Prepared Statements** - SQL injection prevention  
✅ **XSS Protection** - Output escaping via `htmlspecialchars()`  
✅ **Session Security** - HttpOnly, Secure, SameSite cookies  
✅ **Rate Limiting** - Login attempt throttling  
✅ **File Upload Security** - Type validation, size limits, safe storage  
✅ **HTTPS Enforcement** - Redirect to HTTPS in production  

## 🚀 Production Deployment

### 1. Environment Configuration

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourcompany.com

SESSION_SECURE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

### 2. Security Checklist

- [ ] Change default admin credentials
- [ ] Enable HTTPS/SSL
- [ ] Set `APP_DEBUG=false`
- [ ] Configure firewall rules
- [ ] Setup automated backups
- [ ] Configure error logging
- [ ] Disable directory listing
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Hide `.env` from web access

### 3. Performance Optimization

```bash
# Build production assets
npm run build

# Enable PHP OPcache
# Enable Gzip compression
# Configure CDN for static assets
# Setup database connection pooling
```

### 4. Recommended Hosting

- **NeonDB** - PostgreSQL cloud database
- **Vercel/Netlify** - Frontend static assets
- **VPS** - DigitalOcean, Linode, Vultr
- **Shared Hosting** - With PHP 8.2+ support

## 🛠️ Development

### Adding a New Feature

```php
// 1. Create Model
src/Models/Product.php

// 2. Create Repository
src/Repositories/ProductRepository.php

// 3. Create Service
src/Services/ProductService.php

// 4. Create Controller
src/Controllers/Admin/ProductController.php

// 5. Add Route
routes/admin.php

// 6. Create Views
resources/views/admin/products/
```

### Running Tests (Coming Soon)

```bash
composer test
```

## 📝 API Documentation (Coming Soon)

RESTful API endpoints for headless CMS usage.

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add comments for complex logic
- Update documentation

## 📄 License

This project is licensed under the **MIT License**. See [LICENSE](LICENSE) file for details.

## 💬 Support

Need help? Found a bug? Have a suggestion?

- 📧 Email: agustrio1998@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/agustrio1/company-profile-php/issues)
- 💡 Discussions: [GitHub Discussions](https://github.com/agustrio1/company-profile-php/discussions)

## 🌟 Acknowledgments

- [Tailwind CSS](https://tailwindcss.com) - CSS framework
- [Flowbite](https://flowbite.com) - UI components
- [Alpine.js](https://alpinejs.dev) - JavaScript framework
- [NeonDB](https://neon.tech) - PostgreSQL cloud database
- [Resend](https://resend.com) - Email API

## 👨‍💻 Author

**Trio Agus Susanto**

- GitHub: [@agustrio1](https://github.com/agustrio1)
- Email: agustrio1998@gmail.com
- LinkedIn: [Your LinkedIn](https://linkedin.com/in/yourprofile)

---

<div align="center">

**Built with ❤️ using PHP Native, PostgreSQL, Tailwind CSS, and Alpine.js**

[⭐ Star this repo](https://github.com/agustrio1/company-profile-php) if you find it helpful!

</div>