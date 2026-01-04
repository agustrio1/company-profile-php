<?php
$title = 'Tambah User';
ob_start();
?>

<div class="max-w-3xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-body mb-2">
            <a href="<?= url('admin/users') ?>" class="hover:text-fg-brand">Users</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-heading">Tambah User</span>
        </div>
        <h1 class="text-2xl font-bold text-heading">Tambah User Baru</h1>
        <p class="text-sm text-body mt-1">Buat akun user baru dengan role dan akses</p>
    </div>

    <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
        <form action="<?= url('admin/users') ?>" method="POST" x-data="userForm()">
            <?= csrf_field() ?>

            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-heading mb-2">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?= old('name') ?>"
                        required
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="John Doe"
                    >
                    <?php if (hasError('name')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('name') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-heading mb-2">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?= old('email') ?>"
                        required
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="john@example.com"
                    >
                    <?php if (hasError('email')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('email') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-heading mb-2">
                        Password <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            :type="showPassword ? 'text' : 'password'"
                            id="password" 
                            name="password" 
                            required
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5 pr-10"
                            placeholder="Min. 8 karakter"
                        >
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-body hover:text-heading"
                        >
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <?php if (hasError('password')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('password') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Roles -->
                <div>
                    <label class="block text-sm font-medium text-heading mb-2">
                        Roles <span class="text-danger">*</span>
                    </label>
                    <p class="text-xs text-body mb-3">Pilih minimal satu role untuk user ini</p>
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-default rounded-base p-3 bg-neutral-primary">
                        <?php foreach ($roles as $role): ?>
                        <label class="flex items-start p-2 hover:bg-neutral-secondary-medium rounded cursor-pointer transition-colors">
                            <input 
                                type="checkbox" 
                                name="roles[]" 
                                value="<?= $role->id ?>"
                                <?= in_array($role->id, old('roles', [])) ? 'checked' : '' ?>
                                class="mt-0.5 w-4 h-4 text-brand bg-neutral-primary border-default rounded focus:ring-brand focus:ring-2"
                            >
                            <div class="ml-3">
                                <span class="text-sm font-medium text-heading"><?= htmlspecialchars($role->name) ?></span>
                                <?php if ($role->description): ?>
                                    <p class="text-xs text-body mt-0.5"><?= htmlspecialchars($role->description) ?></p>
                                <?php endif; ?>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php if (hasError('roles')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('roles') ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-default">
                <button 
                    type="submit"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-brand hover:bg-brand-strong focus:outline-none focus:ring-4 focus:ring-brand-medium rounded-base transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan User
                </button>
                <a 
                    href="<?= url('admin/users') ?>"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary focus:outline-none focus:ring-4 focus:ring-neutral-tertiary rounded-base transition-colors"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function userForm() {
    return {
        showPassword: false
    }
}
</script>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>