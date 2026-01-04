<?php
$title = 'Dashboard Admin';
ob_start();
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-heading">Dashboard</h1>
            <p class="text-sm text-body mt-1">Selamat datang kembali, <?= htmlspecialchars(auth()->user()->name ?? 'Admin') ?></p>
        </div>
        <div class="text-sm text-body flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <?= date('d F Y') ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Blog -->
        <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-body mb-1">Total Blog</p>
                    <p class="text-2xl font-bold text-heading"><?= $stats['total_blogs'] ?? 0 ?></p>
                    <p class="text-xs text-body mt-1">
                        <span class="text-success-strong"><?= $stats['published_blogs'] ?? 0 ?></span> Dipublikasi
                    </p>
                </div>
                <div class="w-12 h-12 bg-brand-soft rounded-base flex items-center justify-center">
                    <svg class="w-6 h-6 text-fg-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Layanan -->
        <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-body mb-1">Total Layanan</p>
                    <p class="text-2xl font-bold text-heading"><?= $stats['total_services'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-success-soft rounded-base flex items-center justify-center">
                    <svg class="w-6 h-6 text-fg-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Tim -->
        <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-body mb-1">Total Anggota Tim</p>
                    <p class="text-2xl font-bold text-heading"><?= $stats['total_teams'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-warning-soft rounded-base flex items-center justify-center">
                    <svg class="w-6 h-6 text-fg-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-body mb-1">Total Pengguna</p>
                    <p class="text-2xl font-bold text-heading"><?= $stats['total_users'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-danger-soft rounded-base flex items-center justify-center">
                    <svg class="w-6 h-6 text-fg-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
        <h2 class="text-lg font-semibold text-heading mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?= url('admin/blogs/create') ?>" class="flex flex-col items-center justify-center p-4 border border-default rounded-base hover:bg-neutral-secondary-medium hover:border-brand transition-all group">
                <svg class="w-8 h-8 text-body group-hover:text-fg-brand mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium text-heading">Buat Blog</span>
            </a>
            <a href="<?= url('admin/services/create') ?>" class="flex flex-col items-center justify-center p-4 border border-default rounded-base hover:bg-neutral-secondary-medium hover:border-brand transition-all group">
                <svg class="w-8 h-8 text-body group-hover:text-fg-brand mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-medium text-heading">Tambah Layanan</span>
            </a>
            <a href="<?= url('admin/teams/create') ?>" class="flex flex-col items-center justify-center p-4 border border-default rounded-base hover:bg-neutral-secondary-medium hover:border-brand transition-all group">
                <svg class="w-8 h-8 text-body group-hover:text-fg-brand mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="text-sm font-medium text-heading">Tambah Tim</span>
            </a>
            <a href="<?= url('admin/users/create') ?>" class="flex flex-col items-center justify-center p-4 border border-default rounded-base hover:bg-neutral-secondary-medium hover:border-brand transition-all group">
                <svg class="w-8 h-8 text-body group-hover:text-fg-brand mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span class="text-sm font-medium text-heading">Tambah User</span>
            </a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>