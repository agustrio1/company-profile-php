<?php
$title = 'Manajemen Service';
ob_start();
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-heading">Manajemen Service</h1>
            <p class="text-sm text-body mt-1">Kelola layanan perusahaan</p>
        </div>
        <a href="<?= url('admin/services/create') ?>" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-brand hover:bg-brand-strong focus:outline-none focus:ring-4 focus:ring-brand-medium rounded-base transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Service
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-neutral-primary-soft border border-default rounded-base p-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-body" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input 
                type="text" 
                name="search"
                placeholder="Cari berdasarkan judul atau deskripsi..."
                class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full pl-10 p-2.5"
                hx-get="<?= url('admin/services/table') ?>"
                hx-trigger="input changed delay:300ms, search"
                hx-target="#services-table"
                hx-include="[name='search']"
            >
        </div>
    </div>

    <!-- Table Container -->
    <div id="services-table" 
         hx-get="<?= url('admin/services/table') ?>"
         hx-trigger="load, serviceDeleted from:body">
        <!-- Loading indicator -->
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand"></div>
        </div>
    </div>
</div>

<!-- Delete Modal Container -->
<div id="delete-modal"></div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// Global Toast Function
window.showToast = function(message, type = 'success') {
    const icons = {
        success: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
        danger: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
        warning: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
    };
    
    const colors = {
        success: 'text-fg-success bg-success-soft',
        danger: 'text-fg-danger bg-danger-soft',
        warning: 'text-fg-warning bg-warning-soft'
    };
    
    const toast = document.createElement('div');
    toast.className = 'flex items-center w-full max-w-sm p-4 text-body bg-neutral-primary-soft rounded-base shadow-xs border border-default animate-slide-in';
    toast.innerHTML = `
        <div class="inline-flex items-center justify-center shrink-0 w-7 h-7 ${colors[type]} rounded">
            ${icons[type]}
        </div>
        <div class="ms-3 text-sm font-normal">${message}</div>
        <button type="button" class="ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent hover:bg-neutral-secondary-medium rounded text-sm h-8 w-8" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    
    document.getElementById('toast-container').appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
};

// Show session messages
document.addEventListener('DOMContentLoaded', function() {
    <?php if (session('success')): ?>
        showToast('<?= addslashes(session('success')) ?>', 'success');
    <?php endif; ?>
    
    <?php if (session('error')): ?>
        showToast('<?= addslashes(session('error')) ?>', 'danger');
    <?php endif; ?>
});

// HTMX Event - After successful action
document.body.addEventListener('htmx:afterSwap', function(event) {
    const content = event.detail.xhr.responseText;
    if (content.includes('<script>')) {
        const scripts = content.match(/<script>([\s\S]*?)<\/script>/gi);
        if (scripts) {
            scripts.forEach(script => {
                const code = script.replace(/<\/?script>/gi, '');
                eval(code);
            });
        }
    }
});
</script>

<style>
[x-cloak] { display: none !important; }

@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>