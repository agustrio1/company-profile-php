<?php
$title = 'Tambah Team';
ob_start();
?>

<div class="max-w-4xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-body mb-2">
            <a href="<?= url('admin/teams') ?>" class="hover:text-fg-brand">Team</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-heading">Tambah Team</span>
        </div>
        <h1 class="text-2xl font-bold text-heading">Tambah Team</h1>
        <p class="text-sm text-body mt-1">Buat team baru untuk perusahaan</p>
    </div>

    <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
        <form action="<?= url('admin/teams') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-heading mb-2">
                        Nama Team <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?= old('name') ?>"
                        required
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Masukkan nama team..."
                    >
                    <?php if (hasError('name')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('name') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-heading mb-2">
                        Deskripsi <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Deskripsi singkat tentang team..."
                    ><?= old('description') ?></textarea>
                    <?php if (hasError('description')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('description') ?></p>
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
                    Simpan Team
                </button>
                <a 
                    href="<?= url('admin/teams') ?>"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary focus:outline-none focus:ring-4 focus:ring-neutral-tertiary rounded-base transition-colors"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>