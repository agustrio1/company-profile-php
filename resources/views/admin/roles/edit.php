<?php

$title = 'Edit Role';
ob_start();
?>

<div class="space-y-6" x-data="roleForm()">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-heading">Edit Role</h1>
            <p class="text-sm text-body mt-1">Perbarui informasi dan hak akses role <span class="font-medium text-heading"><?= $role->name ?></span></p>
        </div>
        <a href="<?= url('admin/roles') ?>" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary focus:outline-none focus:ring-4 focus:ring-neutral-tertiary rounded-base transition-colors">
            <i class="ri-arrow-left-line mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- System Role Warning -->
    <?php if (in_array($role->name, ['admin', 'super_admin'])): ?>
        <div class="p-4 bg-warning-soft border border-warning-subtle rounded-base">
            <div class="flex items-start">
                <i class="ri-alert-line text-xl text-fg-warning mr-3 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-warning-strong">Role Sistem</p>
                    <p class="text-sm text-body mt-1">Ini adalah role sistem yang dilindungi. Nama role tidak dapat diubah dan role tidak dapat dihapus.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= url("admin/roles/{$role->id}") ?>" method="POST" @submit="loading = true" class="space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        
        <!-- Informasi Role -->
        <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
            <h2 class="text-lg font-semibold text-heading mb-4 flex items-center">
                <i class="ri-information-line mr-2 text-fg-brand"></i>
                Informasi Role
            </h2>
            
            <div class="space-y-4">
                <!-- Nama Role -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-heading">
                        Nama Role <span class="text-danger-strong">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        x-model="name"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5 <?= isset($errors['name']) ? 'border-danger-subtle' : '' ?>" 
                        placeholder="Contoh: editor, moderator, manager"
                        value="<?= old('name', $role->name) ?>"
                        <?= in_array($role->name, ['admin', 'super_admin']) ? 'readonly' : '' ?>
                        required
                    >
                    <?php if (isset($errors['name'])): ?>
                        <p class="mt-2 text-sm text-danger-strong flex items-center">
                            <i class="ri-error-warning-line mr-1"></i>
                            <?= $errors['name'] ?>
                        </p>
                    <?php endif; ?>
                    <?php if (in_array($role->name, ['admin', 'super_admin'])): ?>
                        <p class="mt-1 text-xs text-warning-strong flex items-center">
                            <i class="ri-lock-line mr-1"></i>
                            Nama role sistem tidak dapat diubah
                        </p>
                    <?php else: ?>
                        <p class="mt-1 text-xs text-body flex items-center">
                            <i class="ri-lightbulb-line mr-1"></i>
                            Gunakan huruf kecil dan underscore (_) untuk spasi
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-heading">
                        Deskripsi
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="3"
                        x-model="description"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Deskripsi singkat tentang role ini dan tanggung jawabnya..."
                    ><?= old('description', $role->description) ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                        <p class="mt-2 text-sm text-danger-strong flex items-center">
                            <i class="ri-error-warning-line mr-1"></i>
                            <?= $errors['description'] ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Hak Akses (Permissions) -->
        <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <h2 class="text-lg font-semibold text-heading flex items-center">
                    <i class="ri-shield-check-line mr-2 text-fg-brand"></i>
                    Hak Akses (Permissions)
                </h2>
                <button 
                    type="button"
                    @click="toggleAllPermissions()"
                    class="text-sm text-fg-brand hover:text-fg-brand-strong flex items-center font-medium transition-colors"
                >
                    <i class="ri-checkbox-multiple-line mr-1"></i>
                    <span x-text="allSelected ? 'Hapus Semua' : 'Pilih Semua'"></span>
                </button>
            </div>

            <?php if (isset($errors['permissions'])): ?>
                <div class="mb-4 p-3 bg-danger-soft border border-danger-subtle rounded-base">
                    <p class="text-sm text-danger-strong flex items-center">
                        <i class="ri-error-warning-line mr-1"></i>
                        <?= $errors['permissions'] ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php if (!empty($permissions)): ?>
                    <?php 
                    $rolePermissionIds = array_column($role->permissions ?? [], 'id');
                    ?>
                    <?php foreach ($permissions as $permission): ?>
                        <div class="flex items-start p-3 border border-default rounded-base hover:bg-neutral-secondary-medium hover:border-brand transition-all">
                            <div class="flex items-center h-5">
                                <input 
                                    type="checkbox" 
                                    name="permissions[]" 
                                    value="<?= $permission->id ?>"
                                    id="permission_<?= $permission->id ?>"
                                    x-model="selectedPermissions"
                                    <?= in_array($permission->id, $rolePermissionIds) ? 'checked' : '' ?>
                                    class="w-4 h-4 border border-default rounded bg-neutral-primary focus:ring-3 focus:ring-brand text-brand"
                                >
                            </div>
                            <div class="ml-3 flex-1">
                                <label for="permission_<?= $permission->id ?>" class="text-sm font-medium text-heading cursor-pointer">
                                    <?= ucwords(str_replace('_', ' ', $permission->name)) ?>
                                </label>
                                <?php if ($permission->description): ?>
                                    <p class="text-xs text-body mt-0.5"><?= $permission->description ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-8">
                        <i class="ri-shield-line text-4xl text-body mb-2"></i>
                        <p class="text-sm text-body">Tidak ada hak akses tersedia</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-4 p-3 bg-neutral-secondary-medium rounded-base">
                <p class="text-sm text-body flex items-center">
                    <i class="ri-information-line mr-2 text-fg-brand"></i>
                    Dipilih: <span class="font-medium ml-1" x-text="selectedPermissions.length"></span> dari <?= count($permissions ?? []) ?> hak akses
                </p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div>
                <?php if (!in_array($role->name, ['admin', 'super_admin'])): ?>
                    <button 
                        type="button"
                        @click="confirmDelete()"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-danger hover:bg-danger-strong focus:outline-none focus:ring-4 focus:ring-danger-medium rounded-base transition-colors w-full sm:w-auto"
                    >
                        <i class="ri-delete-bin-line mr-2"></i>
                        Hapus Role
                    </button>
                <?php endif; ?>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a 
                    href="<?= url('admin/roles') ?>" 
                    class="px-5 py-2.5 text-sm font-medium text-center text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary focus:outline-none focus:ring-4 focus:ring-neutral-tertiary rounded-base transition-colors"
                >
                    <i class="ri-close-line mr-1"></i>
                    Batal
                </a>
                <button 
                    type="submit"
                    :disabled="loading || selectedPermissions.length === 0"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-brand hover:bg-brand-strong focus:outline-none focus:ring-4 focus:ring-brand-medium rounded-base disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    <span x-show="!loading" class="flex items-center justify-center">
                        <i class="ri-save-line mr-2"></i>
                        Simpan Perubahan
                    </span>
                    <span x-show="loading" class="flex items-center justify-center">
                        <i class="ri-loader-4-line animate-spin mr-2"></i>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </form>

    <!-- Delete Confirmation Modal -->
    <div 
        x-show="showDeleteModal" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
        @click.self="showDeleteModal = false"
    >
        <div class="bg-neutral-primary-soft border border-default rounded-base p-6 max-w-md w-full" @click.stop>
            <div class="flex items-start mb-4">
                <div class="w-12 h-12 bg-danger-soft rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="ri-alert-line text-2xl text-fg-danger"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-heading mb-1">Hapus Role</h3>
                    <p class="text-sm text-body">Apakah Anda yakin ingin menghapus role <strong><?= $role->name ?></strong>?</p>
                    <p class="text-sm text-body mt-2">Tindakan ini tidak dapat dibatalkan dan akan mempengaruhi semua pengguna yang memiliki role ini.</p>
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
                    class="px-4 py-2 text-sm font-medium text-white bg-danger hover:bg-danger-strong rounded-base transition-colors"
                >
                    <i class="ri-delete-bin-line mr-1"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function roleForm() {
    return {
        name: '<?= old('name', $role->name) ?>',
        description: '<?= old('description', $role->description) ?>',
        selectedPermissions: <?= json_encode(old('permissions') ?? array_column($role->permissions ?? [], 'id')) ?>,
        allSelected: false,
        loading: false,
        showDeleteModal: false,
        
        init() {
            this.checkAllSelected();
            this.$watch('selectedPermissions', () => this.checkAllSelected());
        },
        
        toggleAllPermissions() {
            const allPermissions = <?= json_encode(array_column($permissions ?? [], 'id')) ?>;
            
            if (this.allSelected) {
                this.selectedPermissions = [];
            } else {
                this.selectedPermissions = [...allPermissions];
            }
        },
        
        checkAllSelected() {
            const allPermissions = <?= json_encode(array_column($permissions ?? [], 'id')) ?>;
            this.allSelected = this.selectedPermissions.length === allPermissions.length && allPermissions.length > 0;
        },
        
        confirmDelete() {
            this.showDeleteModal = true;
        },
        
        async deleteRole() {
            try {
                const response = await fetch('<?= url("admin/roles/{$role->id}") ?>', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                if (response.ok) {
                    window.location.href = '<?= url('admin/roles') ?>';
                } else {
                    alert('Gagal menghapus role');
                    this.showDeleteModal = false;
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan saat menghapus role');
                this.showDeleteModal = false;
            }
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>