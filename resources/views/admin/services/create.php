<?php
$title = 'Tambah Service';
ob_start();
?>

<div class="max-w-4xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-body mb-2">
            <a href="<?= url('admin/services') ?>" class="hover:text-fg-brand">Service</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-heading">Tambah Service</span>
        </div>
        <h1 class="text-2xl font-bold text-heading">Tambah Service</h1>
        <p class="text-sm text-body mt-1">Buat layanan baru untuk perusahaan</p>
    </div>

    <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
        <form action="<?= url('admin/services') ?>" method="POST" enctype="multipart/form-data" x-data="serviceForm()">
            <?= csrf_field() ?>

            <div class="space-y-5">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-heading mb-2">
                        Judul Service <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="<?= old('title') ?>"
                        required
                        @input="generateSlug()"
                        x-model="title"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Masukkan judul service..."
                    >
                    <?php if (hasError('title')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('title') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-heading mb-2">
                        Slug <span class="text-danger">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            id="slug" 
                            name="slug" 
                            value="<?= old('slug') ?>"
                            required
                            x-model="slug"
                            :readonly="autoSlug"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="judul-service"
                        >
                        <button 
                            type="button"
                            @click="autoSlug = !autoSlug; if(autoSlug) generateSlug()"
                            class="px-4 py-2.5 text-sm font-medium border rounded-base transition-colors"
                            :class="autoSlug ? 'text-brand border-brand bg-brand-soft' : 'text-body border-default bg-neutral-secondary-medium hover:bg-neutral-tertiary'"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-body">
                        <span x-show="autoSlug">Slug otomatis dibuat dari judul</span>
                        <span x-show="!autoSlug">Mode manual - edit slug sesuai kebutuhan</span>
                    </p>
                    <?php if (hasError('slug')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('slug') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Icon -->
                <div>
                    <label class="block text-sm font-medium text-heading mb-2">
                        Icon <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    
                    <div x-show="iconPreview" class="mb-3 relative inline-block">
                        <img :src="iconPreview" class="w-24 h-24 object-contain border border-default rounded p-2">
                        <button 
                            type="button" 
                            @click="removeIcon()"
                            class="absolute -top-2 -right-2 p-1.5 bg-danger text-white rounded-full hover:bg-danger-strong shadow"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <input 
                        type="file" 
                        name="icon" 
                        accept="image/*"
                        @change="previewIcon($event)"
                        x-ref="iconInput"
                        class="block w-full text-sm text-body border border-default rounded-base cursor-pointer bg-neutral-primary focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-brand-soft file:text-brand hover:file:bg-brand-medium"
                    >
                    <p class="text-xs text-body mt-2">Icon untuk service. Format: PNG, SVG. Maksimal 2MB</p>
                    <?php if (hasError('icon')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('icon') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-heading mb-2">
                        Gambar Service <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    
                    <div x-show="imagePreview" class="mb-3 relative">
                        <img :src="imagePreview" class="w-full h-48 object-cover rounded-base border border-default">
                        <button 
                            type="button" 
                            @click="removeImage()"
                            class="absolute top-2 right-2 p-1.5 bg-danger text-white rounded-full hover:bg-danger-strong shadow"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <input 
                        type="file" 
                        name="image" 
                        accept="image/*"
                        @change="previewImage($event)"
                        x-ref="imageInput"
                        class="block w-full text-sm text-body border border-default rounded-base cursor-pointer bg-neutral-primary focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-brand-soft file:text-brand hover:file:bg-brand-medium"
                    >
                    <p class="text-xs text-body mt-2">Gambar utama service. Format: JPG, PNG, WebP. Maksimal 5MB</p>
                    <?php if (hasError('image')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('image') ?></p>
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
                        rows="5"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Deskripsi singkat tentang service..."
                    ><?= old('description') ?></textarea>
                    <?php if (hasError('description')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('description') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Is Featured -->
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="is_featured" 
                            value="1"
                            <?= old('is_featured') ? 'checked' : '' ?>
                            class="w-4 h-4 text-brand bg-neutral-primary border-default rounded focus:ring-brand focus:ring-2"
                        >
                        <span class="ml-2 text-sm text-heading">Tampilkan sebagai Featured Service</span>
                    </label>
                    <p class="text-xs text-body mt-1 ml-6">Service akan ditampilkan di halaman utama</p>
                </div>

                <!-- SEO Section -->
                <div class="border-t border-default pt-5">
                    <h3 class="text-lg font-semibold text-heading mb-4">SEO Optimization</h3>
                    
                    <!-- Meta Title -->
                    <div class="mb-4">
                        <label for="seo_title" class="block text-sm font-medium text-heading mb-2">
                            Meta Title <span class="text-xs text-body font-normal">(Opsional)</span>
                        </label>
                        <input 
                            type="text" 
                            id="seo_title" 
                            name="seo[title]" 
                            value="<?= old('seo.title') ?>"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="SEO title (kosongkan untuk gunakan judul service)"
                        >
                        <p class="mt-1 text-xs text-body">Optimal: 50-60 karakter</p>
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-4">
                        <label for="seo_description" class="block text-sm font-medium text-heading mb-2">
                            Meta Description <span class="text-xs text-body font-normal">(Opsional)</span>
                        </label>
                        <textarea 
                            id="seo_description" 
                            name="seo[description]" 
                            rows="3"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="Deskripsi singkat untuk mesin pencari..."
                        ><?= old('seo.description') ?></textarea>
                        <p class="mt-1 text-xs text-body">Optimal: 150-160 karakter</p>
                    </div>

                    <!-- Meta Keywords -->
                    <div>
                        <label for="seo_keywords" class="block text-sm font-medium text-heading mb-2">
                            Meta Keywords <span class="text-xs text-body font-normal">(Opsional)</span>
                        </label>
                        <input 
                            type="text" 
                            id="seo_keywords" 
                            name="seo[keywords]" 
                            value="<?= old('seo.keywords') ?>"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="keyword1, keyword2, keyword3"
                        >
                        <p class="mt-1 text-xs text-body">Pisahkan dengan koma</p>
                    </div>
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
                    Simpan Service
                </button>
                <a 
                    href="<?= url('admin/services') ?>"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary focus:outline-none focus:ring-4 focus:ring-neutral-tertiary rounded-base transition-colors"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function serviceForm() {
    return {
        title: '<?= old('title') ?>',
        slug: '<?= old('slug') ?>',
        autoSlug: true,
        iconPreview: null,
        imagePreview: null,
        
        generateSlug() {
            if (!this.autoSlug) return;
            
            this.slug = this.title
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
        },

        previewIcon(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('File maksimal 2MB');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.iconPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        removeIcon() {
            this.iconPreview = null;
            this.$refs.iconInput.value = '';
        },

        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('File maksimal 5MB');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        removeImage() {
            this.imagePreview = null;
            this.$refs.imageInput.value = '';
        }
    }
}
</script>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>