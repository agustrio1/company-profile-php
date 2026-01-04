<?php

$title = 'Manajemen Role';
ob_start();
?>

<div class="space-y-6" x-data="rolesManager()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-heading">Manajemen Role</h1>
            <p class="text-sm text-body mt-1">Kelola role dan hak akses pengguna sistem</p>
        </div>
        <a href="<?= url('admin/roles/create') ?>" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-brand hover:bg-brand-strong focus:outline-none focus:ring-4 focus:ring-brand-medium rounded-base transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Role
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-neutral-primary-soft border border-default rounded-base p-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-body" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="search"
                        @input="filterRoles()"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full pl-10 p-2.5" 
                        placeholder="Cari berdasarkan nama atau deskripsi role..."
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="bg-neutral-primary-soft border border-default rounded-base overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-body">
                <thead class="text-xs uppercase bg-neutral-secondary-medium text-heading">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Role</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                        <th scope="col" class="px-6 py-3">Jumlah Izin</th>
                        <th scope="col" class="px-6 py-3">Dibuat</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="role in filteredRoles" :key="role.id">
                        <tr class="border-t border-default hover:bg-neutral-secondary-medium transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-brand-soft rounded flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-fg-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-medium text-heading" x-text="role.name"></span>
                                        <template x-if="['admin', 'super_admin'].includes(role.name)">
                                            <span class="ml-2 text-xs px-2 py-0.5 bg-warning-soft text-fg-warning rounded">Sistem</span>
                                        </template>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-body" x-text="role.description || '-'"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-neutral-tertiary text-heading rounded" x-text="getPermissionCount(role) + ' izin'"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-body" x-text="formatDate(role.created_at)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a 
                                        :href="`<?= url('admin/roles') ?>/${role.id}/edit`" 
                                        class="p-2 text-fg-brand hover:text-fg-brand-strong hover:bg-brand-soft rounded transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button 
                                        @click="confirmDelete(role)"
                                        class="p-2 text-fg-danger hover:text-danger-strong hover:bg-danger-soft rounded transition-colors"
                                        :disabled="['admin', 'super_admin'].includes(role.name)"
                                        :class="{ 'opacity-50 cursor-not-allowed': ['admin', 'super_admin'].includes(role.name) }"
                                        title="Hapus"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-if="filteredRoles.length === 0">
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-body mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-body font-medium">Tidak ada role ditemukan</p>
                                    <p class="text-sm text-body mt-1" x-show="search">Coba ubah kata kunci pencarian</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal - FIXED: Background color dari CSS variables -->
    <div 
        x-show="showDeleteModal" 
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background-color: rgba(0, 0, 0, 0.5);"
        @click.self="showDeleteModal = false"
    >
        <div 
            class="bg-neutral-primary-soft border border-default rounded-base shadow-lg p-6 max-w-md w-full"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
        >
            <div class="flex items-start mb-4">
                <div class="w-12 h-12 bg-danger-soft rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <svg class="w-6 h-6 text-fg-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-heading mb-1">Hapus Role</h3>
                    <p class="text-sm text-body">Apakah Anda yakin ingin menghapus role <strong x-text="deleteTarget?.name"></strong>?</p>
                    <p class="text-sm text-body mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button 
                    @click="showDeleteModal = false"
                    class="px-4 py-2 text-sm font-medium text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary rounded-base transition-colors"
                >
                    Batal
                </button>
                <button 
                    @click="deleteRole()"
                    :disabled="isDeleting"
                    :class="{ 'opacity-50 cursor-not-allowed': isDeleting }"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-danger hover:bg-danger-strong rounded-base transition-colors"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span x-text="isDeleting ? 'Menghapus...' : 'Hapus'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
function rolesManager() {
    return {
        roles: <?= json_encode($roles ?? []) ?>,
        filteredRoles: [],
        search: '',
        showDeleteModal: false,
        deleteTarget: null,
        isDeleting: false,
        
        init() {
            this.filteredRoles = this.roles;
        },
        
        filterRoles() {
            if (!this.search) {
                this.filteredRoles = this.roles;
                return;
            }
            
            const searchLower = this.search.toLowerCase();
            this.filteredRoles = this.roles.filter(role => 
                role.name.toLowerCase().includes(searchLower) ||
                (role.description && role.description.toLowerCase().includes(searchLower))
            );
        },
        
        getPermissionCount(role) {
            if (!role) return 0;
            
            if (Array.isArray(role.permissions)) {
                return role.permissions.length;
            }
            
            if (role.permissions && typeof role.permissions === 'object') {
                if (role.permissions.length !== undefined) {
                    return role.permissions.length;
                }
                return Object.keys(role.permissions).length;
            }
            
            if (role.permission_count !== undefined) {
                return parseInt(role.permission_count) || 0;
            }
            
            if (role.permissions_count !== undefined) {
                return parseInt(role.permissions_count) || 0;
            }
            
            return 0;
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric' 
            });
        },
        
        confirmDelete(role) {
            if (['admin', 'super_admin'].includes(role.name)) {
                showToast('Role sistem tidak dapat dihapus', 'warning');
                return;
            }
            this.deleteTarget = role;
            this.showDeleteModal = true;
        },
        
        async deleteRole() {
            if (!this.deleteTarget || this.isDeleting) return;
            
            this.isDeleting = true;
            
            try {
                const url = `<?= url('admin/roles') ?>/${this.deleteTarget.id}`;
                
                // FIX: Gunakan form data untuk mengirim _method=DELETE
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
                
                const response = await fetch(url, {
                    method: 'POST', // Gunakan POST dengan _method=DELETE
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                const data = await response.json().catch(() => ({}));
                
                if (response.ok) {
                    this.roles = this.roles.filter(role => role.id !== this.deleteTarget.id);
                    this.filterRoles();
                    showToast(data.message || 'Role berhasil dihapus', 'success');
                    this.showDeleteModal = false;
                    this.deleteTarget = null;
                } else {
                    showToast(data.message || 'Gagal menghapus role', 'danger');
                }
            } catch (error) {
                showToast('Terjadi kesalahan saat menghapus role', 'danger');
            } finally {
                this.isDeleting = false;
            }
        }
    }
}

function showToast(message, type = 'success') {
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
        <button type="button" class="ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium rounded text-sm h-8 w-8" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    
    document.getElementById('toast-container').appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

document.addEventListener('DOMContentLoaded', function() {
    <?php if (session('success')): ?>
        showToast('<?= addslashes(session('success')) ?>', 'success');
    <?php endif; ?>
    
    <?php if (session('error')): ?>
        showToast('<?= addslashes(session('error')) ?>', 'danger');
    <?php endif; ?>
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