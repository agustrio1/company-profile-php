<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Meta Tags -->
    <title><?= htmlspecialchars($seo['title'] ?? $title ?? 'Company Profile') ?></title>
    <meta name="description" content="<?= htmlspecialchars($seo['description'] ?? 'Professional company profile and services') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($seo['keywords'] ?? 'company, services, business') ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($currentUrl ?? '') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($seo['title'] ?? $title ?? 'Company Profile') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($seo['description'] ?? 'Professional company profile and services') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($seo['image'] ?? asset('images/logo.svg')) ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= htmlspecialchars($currentUrl ?? '') ?>">
    <meta property="twitter:title" content="<?= htmlspecialchars($seo['title'] ?? $title ?? 'Company Profile') ?>">
    <meta property="twitter:description" content="<?= htmlspecialchars($seo['description'] ?? 'Professional company profile and services') ?>">
    <meta property="twitter:image" content="<?= htmlspecialchars($seo['image'] ?? asset('images/logo.svg')) ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl ?? $currentUrl ?? '') ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($company->logo ?? '/images/logo.svg') ?>">
    
    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?= htmlspecialchars($company->name ?? 'Company Name') ?>",
        "url": "<?= htmlspecialchars($company->website ?? '') ?>",
        "logo": "<?= htmlspecialchars($company->getLogoUrl() ?? asset('images/logo.svg')) ?>",
        <?php if (!empty($company->description)): ?>
        "description": "<?= htmlspecialchars($company->description) ?>",
        <?php endif; ?>
        <?php if (!empty($company->founded_year)): ?>
        "foundingDate": "<?= $company->founded_year ?>",
        <?php endif; ?>
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?= htmlspecialchars($company->address ?? '') ?>"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "<?= htmlspecialchars($company->phone ?? '') ?>",
            "contactType": "customer service",
            "email": "<?= htmlspecialchars($company->email ?? '') ?>"
        },
        "sameAs": [
            "<?= htmlspecialchars($company->website ?? '') ?>"
        ]
    }
    </script>
    
    <?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            <?php foreach ($breadcrumbs as $index => $crumb): ?>
            {
                "@type": "ListItem",
                "position": <?= $index + 1 ?>,
                "name": "<?= htmlspecialchars($crumb['name']) ?>",
                "item": "<?= htmlspecialchars($crumb['url']) ?>"
            }<?= $index < count($breadcrumbs) - 1 ? ',' : '' ?>
            <?php endforeach; ?>
        ]
    }
    </script>
    <?php endif; ?>
    
    <?php if (isset($blog) && $blog): ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "<?= htmlspecialchars($blog->title) ?>",
        "image": "<?= htmlspecialchars($blog->getThumbnailUrl() ?? '') ?>",
        "datePublished": "<?= $blog->created_at ?>",
        "dateModified": "<?= $blog->updated_at ?>",
        "author": {
            "@type": "Person",
            "name": "<?= htmlspecialchars($blog->author_name ?? 'Admin') ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "<?= htmlspecialchars($company->name ?? 'Company Name') ?>",
            "logo": {
                "@type": "ImageObject",
                "url": "<?= htmlspecialchars($company->getLogoUrl() ?? asset('images/logo.svg')) ?>"
            }
        },
        "description": "<?= htmlspecialchars($blog->seo->description ?? strip_tags(substr($blog->content, 0, 160))) ?>"
    }
    </script>
    <?php endif; ?>
    
    <?= vite() ?>
    
    <style>
        .htmx-swapping {
            opacity: 0;
            transition: opacity 200ms ease-out;
        }
        
        .htmx-settling {
            opacity: 1;
            transition: opacity 200ms ease-in;
        }
        
        .htmx-request .htmx-indicator {
            display: inline-block;
        }
        
        .htmx-indicator {
            display: none;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1;
        }
        
        .navbar-scrolled {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            backdrop-filter: blur(8px);
            background-color: rgba(255, 255, 255, 0.98);
        }
        
        @keyframes loading-progress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }
        
        .loading-bar {
            animation: loading-progress 1s ease-in-out;
        }
    </style>
</head>
<body class="bg-white" x-data="{ 
    mobileMenuOpen: false,
    scrolled: false,
    currentPath: window.location.pathname,
    init() {
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 20;
        });
    }
}">
    
    <!-- Loading Indicator -->
    <div id="loading-indicator" class="htmx-indicator fixed top-0 left-0 right-0 h-0.5 bg-blue-600 z-50">
        <div class="h-full bg-blue-700 loading-bar"></div>
    </div>
    
    <!-- Navbar -->
    <nav class="fixed w-full z-40 top-0 start-0 bg-white border-b border-gray-200 transition-all duration-300"
         :class="scrolled ? 'navbar-scrolled' : ''">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" 
                       hx-get="/" 
                       hx-target="#main-content" 
                       hx-swap="innerHTML transition:true"
                       hx-push-url="true"
                       hx-indicator="#loading-indicator"
                       @click="currentPath = '/'; mobileMenuOpen = false"
                       class="flex items-center space-x-3">
                        <img src="<?= htmlspecialchars($company->getLogoUrl() ?? '/images/logo.svg') ?>" 
                             class="h-8 w-8" 
                             alt="<?= htmlspecialchars($company->name ?? 'Company Logo') ?>">
                        <span class="text-xl font-semibold text-gray-900">
                            <?= htmlspecialchars($company->name ?? 'Company Name') ?>
                        </span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" 
                       hx-get="/" 
                       hx-target="#main-content" 
                       hx-swap="innerHTML transition:true"
                       hx-push-url="true"
                       hx-indicator="#loading-indicator"
                       @click="currentPath = '/'"
                       :class="currentPath === '/' ? 'text-blue-600 font-medium' : 'text-gray-700 hover:text-gray-900'"
                       class="text-sm transition-colors">
                        Home
                    </a>
                    <a href="/about" 
                       hx-get="/about" 
                       hx-target="#main-content" 
                       hx-swap="innerHTML transition:true"
                       hx-push-url="true"
                       hx-indicator="#loading-indicator"
                       @click="currentPath = '/about'"
                       :class="currentPath === '/about' ? 'text-blue-600 font-medium' : 'text-gray-700 hover:text-gray-900'"
                       class="text-sm transition-colors">
                        About
                    </a>
                    <a href="/services" 
                       hx-get="/services" 
                       hx-target="#main-content" 
                       hx-swap="innerHTML transition:true"
                       hx-push-url="true"
                       hx-indicator="#loading-indicator"
                       @click="currentPath = '/services'"
                       :class="currentPath === '/services' ? 'text-blue-600 font-medium' : 'text-gray-700 hover:text-gray-900'"
                       class="text-sm transition-colors">
                        Services
                    </a>
                    <a href="/blog" 
                       hx-get="/blog" 
                       hx-target="#main-content" 
                       hx-swap="innerHTML transition:true"
                       hx-push-url="true"
                       hx-indicator="#loading-indicator"
                       @click="currentPath = '/blog'"
                       :class="currentPath === '/blog' ? 'text-blue-600 font-medium' : 'text-gray-700 hover:text-gray-900'"
                       class="text-sm transition-colors">
                        Blog
                    </a>
                    <a href="/contact" 
                       hx-get="/contact" 
                       hx-target="#main-content" 
                       hx-swap="innerHTML transition:true"
                       hx-push-url="true"
                       hx-indicator="#loading-indicator"
                       @click="currentPath = '/contact'"
                       :class="currentPath === '/contact' ? 'text-blue-600 font-medium' : 'text-gray-700 hover:text-gray-900'"
                       class="text-sm transition-colors">
                        Contact
                    </a>
                    
                    <a href="tel:<?= htmlspecialchars($company->phone ?? '') ?>" 
                       class="ml-4 px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                        Hubungi Kami
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        type="button" 
                        class="md:hidden p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                    <svg class="w-6 h-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="w-6 h-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="md:hidden border-t border-gray-200 bg-white" 
             x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             style="display: none;">
            <div class="px-4 py-3 space-y-1">
                <a href="/" 
                   hx-get="/" 
                   hx-target="#main-content" 
                   hx-swap="innerHTML transition:true"
                   hx-push-url="true"
                   hx-indicator="#loading-indicator"
                   @click="currentPath = '/'; mobileMenuOpen = false"
                   :class="currentPath === '/' ? 'bg-gray-100 text-blue-600 font-medium' : 'text-gray-700'"
                   class="block px-3 py-2 rounded-md text-base">
                    Home
                </a>
                <a href="/about" 
                   hx-get="/about" 
                   hx-target="#main-content" 
                   hx-swap="innerHTML transition:true"
                   hx-push-url="true"
                   hx-indicator="#loading-indicator"
                   @click="currentPath = '/about'; mobileMenuOpen = false"
                   :class="currentPath === '/about' ? 'bg-gray-100 text-blue-600 font-medium' : 'text-gray-700'"
                   class="block px-3 py-2 rounded-md text-base">
                    About
                </a>
                <a href="/services" 
                   hx-get="/services" 
                   hx-target="#main-content" 
                   hx-swap="innerHTML transition:true"
                   hx-push-url="true"
                   hx-indicator="#loading-indicator"
                   @click="currentPath = '/services'; mobileMenuOpen = false"
                   :class="currentPath === '/services' ? 'bg-gray-100 text-blue-600 font-medium' : 'text-gray-700'"
                   class="block px-3 py-2 rounded-md text-base">
                    Services
                </a>
                <a href="/blog" 
                   hx-get="/blog" 
                   hx-target="#main-content" 
                   hx-swap="innerHTML transition:true"
                   hx-push-url="true"
                   hx-indicator="#loading-indicator"
                   @click="currentPath = '/blog'; mobileMenuOpen = false"
                   :class="currentPath === '/blog' ? 'bg-gray-100 text-blue-600 font-medium' : 'text-gray-700'"
                   class="block px-3 py-2 rounded-md text-base">
                    Blog
                </a>
                <a href="/contact" 
                   hx-get="/contact" 
                   hx-target="#main-content" 
                   hx-swap="innerHTML transition:true"
                   hx-push-url="true"
                   hx-indicator="#loading-indicator"
                   @click="currentPath = '/contact'; mobileMenuOpen = false"
                   :class="currentPath === '/contact' ? 'bg-gray-100 text-blue-600 font-medium' : 'text-gray-700'"
                   class="block px-3 py-2 rounded-md text-base">
                    Contact
                </a>
                <div class="pt-3">
                    <a href="tel:<?= htmlspecialchars($company->phone ?? '') ?>"
                       class="block w-full px-5 py-2 text-center text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <main id="main-content" class="pt-16">
        <?= $content ?? '' ?>
    </main>

    <!-- Clean Professional Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="<?= htmlspecialchars($company->getLogoUrl() ?? '/images/logo.svg') ?>" 
                             class="h-8 w-8" 
                             alt="<?= htmlspecialchars($company->name ?? 'Company Logo') ?>">
                        <span class="text-lg font-semibold text-gray-900">
                            <?= htmlspecialchars($company->name ?? 'Company Name') ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-6 max-w-md">
                        <?= htmlspecialchars($company->description ?? 'Professional business consulting services to help your organization grow and succeed.') ?>
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="/about" 
                               hx-get="/about" 
                               hx-target="#main-content" 
                               hx-swap="innerHTML transition:true"
                               hx-push-url="true"
                               hx-indicator="#loading-indicator"
                               class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                About Us
                            </a>
                        </li>
                        <li>
                            <a href="/services" 
                               hx-get="/services" 
                               hx-target="#main-content" 
                               hx-swap="innerHTML transition:true"
                               hx-push-url="true"
                               hx-indicator="#loading-indicator"
                               class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                Our Services
                            </a>
                        </li>
                        <li>
                            <a href="/blog" 
                               hx-get="/blog" 
                               hx-target="#main-content" 
                               hx-swap="innerHTML transition:true"
                               hx-push-url="true"
                               hx-indicator="#loading-indicator"
                               class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                Blog & News
                            </a>
                        </li>
                        <li>
                            <a href="/contact" 
                               hx-get="/contact" 
                               hx-target="#main-content" 
                               hx-swap="innerHTML transition:true"
                               hx-push-url="true"
                               hx-indicator="#loading-indicator"
                               class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                Contact Us
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Contact</h3>
                    <ul class="space-y-3">
                        <li class="text-sm text-gray-600">
                            <?= htmlspecialchars($company->address ?? '123 Business Street, City, Country') ?>
                        </li>
                        <li>
                            <a href="mailto:<?= htmlspecialchars($company->email ?? 'info@company.com') ?>" 
                               class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                <?= htmlspecialchars($company->email ?? 'info@company.com') ?>
                            </a>
                        </li>
                        <li>
                            <a href="tel:<?= htmlspecialchars($company->phone ?? '') ?>" 
                               class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                <?= htmlspecialchars($company->phone ?? '+1 (234) 567-890') ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600 space-y-4 md:space-y-0">
                    <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($company->name ?? 'Company Name') ?>. All rights reserved.</p>
                    <div class="flex space-x-6">
                        <a href="/privacy-policy" 
                           hx-get="/privacy-policy" 
                           hx-target="#main-content" 
                           hx-swap="innerHTML transition:true"
                           hx-push-url="true"
                           hx-indicator="#loading-indicator"
                           class="hover:text-gray-900 transition-colors">
                            Privacy Policy
                        </a>
                        <a href="/terms" 
                           hx-get="/terms" 
                           hx-target="#main-content" 
                           hx-swap="innerHTML transition:true"
                           hx-push-url="true"
                           hx-indicator="#loading-indicator"
                           class="hover:text-gray-900 transition-colors">
                            Terms of Service
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- HTMX Config -->
    <script>
        document.body.addEventListener('htmx:configRequest', (event) => {
            event.detail.headers['X-Requested-With'] = 'XMLHttpRequest';
        });
        
        document.body.addEventListener('htmx:afterSwap', (event) => {
            if (event.detail.target.id === 'main-content') {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
        
        window.addEventListener('popstate', () => {
            htmx.ajax('GET', window.location.pathname, {
                target: '#main-content',
                swap: 'innerHTML transition:true'
            });
        });
        
        document.body.addEventListener('htmx:afterSettle', () => {
            const currentPath = window.location.pathname;
            document.querySelectorAll('a[hx-get]').forEach(link => {
                const href = link.getAttribute('hx-get');
                if (href === currentPath) {
                    link.classList.add('text-blue-600', 'font-medium');
                }
            });
        });
    </script>

</body>
</html>