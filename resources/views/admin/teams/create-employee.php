<?php
$title = 'Tambah Anggota Team';
ob_start();
?>

<div class="max-w-4xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-body mb-2">
            <a href="<?= url('admin/teams') ?>" class="hover:text-fg-brand">Team</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-heading">Tambah Anggota</span>
        </div>
        <h1 class="text-2xl font-bold text-heading">Tambah Anggota Team</h1>
        <p class="text-sm text-body mt-1">Tambahkan anggota baru ke team <strong><?= htmlspecialchars($team->name ?? '') ?></strong></p>
    </div>

    <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
        <form action="<?= url('admin/employees') ?>" method="POST" enctype="multipart/form-data" x-data="employeeForm()">
            <?= csrf_field() ?>

            <input type="hidden" name="team_id" value="<?= $team->id ?? '' ?>">

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
                        placeholder="Masukkan nama lengkap..."
                    >
                    <?php if (hasError('name')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('name') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-heading mb-2">
                        Posisi/Jabatan <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    <input 
                        type="text" 
                        id="position" 
                        name="position" 
                        value="<?= old('position') ?>"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Contoh: Manager, Developer, Designer..."
                    >
                    <?php if (hasError('position')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('position') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Team -->
                <div>
                    <label for="team_id" class="block text-sm font-medium text-heading mb-2">
                        Team <span class="text-danger">*</span>
                    </label>
                    <select 
                        id="team_id" 
                        name="team_id" 
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                    >
                        <?php foreach ($teams as $t): ?>
                            <option value="<?= $t->id ?>" <?= ($t->id === ($team->id ?? old('team_id'))) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (hasError('team_id')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('team_id') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Photo -->
                <div>
                    <label class="block text-sm font-medium text-heading mb-2">
                        Foto <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    
                    <div x-show="photoPreview" class="mb-3 relative inline-block">
                        <img :src="photoPreview" class="w-32 h-32 object-cover rounded-full border border-default">
                        <button 
                            type="button" 
                            @click="removePhoto()"
                            class="absolute -top-2 -right-2 p-1.5 bg-danger text-white rounded-full hover:bg-danger-strong shadow"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <input 
                        type="file" 
                        name="photo" 
                        accept="image/*"
                        @change="previewPhoto($event)"
                        x-ref="photoInput"
                        class="block w-full text-sm text-body border border-default rounded-base cursor-pointer bg-neutral-primary focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-brand-soft file:text-brand hover:file:bg-brand-medium"
                    >
                    <p class="text-xs text-body mt-2">Foto anggota. Format: JPG, PNG, WebP. Maksimal 5MB</p>
                    <?php if (hasError('photo')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('photo') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-heading mb-2">
                        Bio <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    <textarea 
                        id="bio" 
                        name="bio" 
                        rows="4"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Bio singkat anggota..."
                    ><?= old('bio') ?></textarea>
                    <?php if (hasError('bio')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('bio') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-heading mb-2">
                        Urutan Tampilan <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    <input 
                        type="number" 
                        id="sort_order" 
                        name="sort_order" 
                        value="<?= old('sort_order', 0) ?>"
                        min="0"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="0"
                    >
                    <p class="text-xs text-body mt-1">Angka lebih kecil akan ditampilkan lebih dulu</p>
                    <?php if (hasError('sort_order')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('sort_order') ?></p>
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
                    Simpan Anggota
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

<script>
function employeeForm() {
    return {
        photoPreview: null,
        
        previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('File maksimal 5MB');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        removePhoto() {
            this.photoPreview = null;
            this.$refs.photoInput.value = '';
        }
    }
}
</script>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>