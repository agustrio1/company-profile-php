<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <?= vite() ?>
</head>
<body class="bg-neutral-primary" x-data="{ sidebarOpen: false, dropdowns: {} }">
    
    <!-- Navbar -->
    <nav class="fixed top-0 z-50 w-full bg-neutral-primary-soft border-b border-default">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <!-- Sidebar Toggle Button -->
                    <button 
                        @click="sidebarOpen = !sidebarOpen"
                        type="button" 
                        class="inline-flex items-center p-2 text-sm text-body rounded-base hover:bg-neutral-secondary-medium focus:outline-none focus:ring-2 focus:ring-neutral-tertiary"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <!-- Logo -->
                    <a href="<?= url('admin/dashboard') ?>" class="flex ml-2 md:mr-24">
                        <img src="/images/logo.svg" class="h-8 mr-3" alt="Logo">
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-heading">Admin Panel</span>
                    </a>
                </div>
                
                <!-- User Dropdown -->
                <div class="flex items-center" x-data="{ userMenuOpen: false }">
                    <button 
                        @click="userMenuOpen = !userMenuOpen"
                        type="button" 
                        class="flex text-sm bg-neutral-secondary-medium rounded-full focus:ring-4 focus:ring-neutral-tertiary"
                    >
                        <span class="sr-only">Open user menu</span>
                        <div class="w-8 h-8 rounded-full bg-brand text-white flex items-center justify-center font-medium">
                            <?= strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) ?>
                        </div>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div 
                        x-show="userMenuOpen"
                        @click.away="userMenuOpen = false"
                        x-transition
                        class="absolute right-4 top-14 z-50 text-base list-none bg-neutral-primary-soft divide-y divide-default rounded-base shadow-xs border border-default"
                        style="display: none;"
                    >
                        <div class="px-4 py-3">
                            <p class="text-sm text-heading"><?= htmlspecialchars(auth()->user()->name ?? 'Admin') ?></p>
                            <p class="text-sm font-medium text-body truncate"><?= htmlspecialchars(auth()->user()->email ?? '') ?></p>
                        </div>
                        <ul class="py-1">
                            <li>
                                <a href="<?= url('admin/dashboard') ?>" class="block px-4 py-2 text-sm text-body hover:bg-neutral-secondary-medium hover:text-heading">
                                    <i class="ri-dashboard-line mr-2"></i>Dashboard
                                </a>
                            </li>
                            <li>
                                <form action="<?= url('logout') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-danger hover:bg-danger-soft">
                                        <i class="ri-logout-box-line mr-2"></i>Sign out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-neutral-primary-soft border-r border-default lg:translate-x-0"
    >
        <div class="h-full px-3 pb-4 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <li>
                    <a href="<?= url('admin/dashboard') ?>" class="flex items-center p-2 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-body group-hover:text-fg-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Blog Menu -->
                <li>
                    <button 
                        @click="dropdowns.blog = !dropdowns.blog"
                        type="button" 
                        class="flex items-center w-full p-2 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group"
                    >
                        <svg class="w-5 h-5 text-body group-hover:text-fg-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="flex-1 ms-3 text-left">Blog</span>
                        <svg class="w-5 h-5 transition-transform" :class="dropdowns.blog ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul x-show="dropdowns.blog" x-transition class="py-2 space-y-2">
                        <li>
                            <a href="<?= url('admin/blogs') ?>" class="flex items-center w-full p-2 pl-11 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                                All Blogs
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/blogs/create') ?>" class="flex items-center w-full p-2 pl-11 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                                Add New
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/blog-categories') ?>" class="flex items-center w-full p-2 pl-11 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                                Categories
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Services Menu -->
                <li>
                    <button 
                        @click="dropdowns.services = !dropdowns.services"
                        type="button" 
                        class="flex items-center w-full p-2 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group"
                    >
                        <svg class="w-5 h-5 text-body group-hover:text-fg-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="flex-1 ms-3 text-left">Services</span>
                        <svg class="w-5 h-5 transition-transform" :class="dropdowns.services ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul x-show="dropdowns.services" x-transition class="py-2 space-y-2">
                        <li>
                            <a href="<?= url('admin/services') ?>" class="flex items-center w-full p-2 pl-11 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                                All Services
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/services/create') ?>" class="flex items-center w-full p-2 pl-11 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                                Add New
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Team Menu -->
                <li>
                    <button 
                        @click="dropdowns.team = !dropdowns.team"
                        type="button" 
                        class="flex items-center w-full p-2 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group"
                    >
                        <svg class="w-5 h-5 text-body group-hover:text-fg-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="flex-1 ms-3 text-left">Team</span>
                        <svg class="w-5 h-5 transition-transform" :class="dropdowns.team ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul x-show="dropdowns.team" x-transition class="py-2 space-y-2">
                        <li>
                            <a href="<?= url('admin/teams') ?>" class="flex items-center w-full p-2 pl-11 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                                All Members
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('admin/teams/create') ?>" class="flex items-center w-full p-2 pl-11 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                                Add New
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Company Settings -->
                <li>
                    <a href="<?= url('admin/company/edit') ?>" class="flex items-center p-2 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-body group-hover:text-fg-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="flex-1 ms-3">Company Settings</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="pt-4 mt-4 space-y-2 border-t border-default">
                    <!-- Users -->
                    <a href="<?= url('admin/users') ?>" class="flex items-center p-2 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-body group-hover:text-fg-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="flex-1 ms-3">Users</span>
                    </a>
                </li>

                <!-- Roles -->
                <li>
                    <a href="<?= url('admin/roles') ?>" class="flex items-center p-2 text-body rounded-base hover:bg-neutral-secondary-medium hover:text-fg-brand group">
                        <svg class="w-5 h-5 text-body group-hover:text-fg-brand transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="flex-1 ms-3">Roles & Permissions</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Overlay for mobile - NO DARK OVERLAY -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        class="fixed inset-0 z-30 lg:hidden"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;"
    ></div>

    <!-- Main Content -->
    <div class="p-4 lg:ml-64 mt-14">
        <div class="p-6 rounded-base bg-neutral-primary-soft">
            <?= $content ?? '' ?>
        </div>
    </div>

</body>
</html>