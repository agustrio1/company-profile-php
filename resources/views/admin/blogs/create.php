<?php
$title = 'Tambah Blog';
ob_start();
?>

<div class="max-w-4xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-body mb-2">
            <a href="<?= url('admin/blogs') ?>" class="hover:text-fg-brand">Blog</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-heading">Tambah Blog</span>
        </div>
        <h1 class="text-2xl font-bold text-heading">Tambah Blog Post</h1>
        <p class="text-sm text-body mt-1">Buat blog post baru untuk website</p>
    </div>

    <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
        <form action="<?= url('admin/blogs') ?>" method="POST" enctype="multipart/form-data" x-data="blogForm()">
            <?= csrf_field() ?>

            <div class="space-y-5">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-heading mb-2">
                        Judul Blog <span class="text-danger">*</span>
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
                        placeholder="Masukkan judul blog..."
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
                            placeholder="judul-blog"
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

                <!-- Category with Alpine Search Select -->
                <div x-data="categorySelect()">
                    <label class="block text-sm font-medium text-heading mb-2">
                        Kategori <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    
                    <div class="relative">
                        <button 
                            type="button"
                            @click="open = !open"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand flex items-center justify-between w-full p-2.5"
                        >
                            <span x-text="selectedLabel"></span>
                            <svg class="w-2.5 h-2.5 ms-2.5" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div 
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute z-10 mt-1 w-full bg-neutral-primary rounded-base shadow-lg border border-default"
                        >
                            <!-- Search -->
                            <div class="p-3 border-b border-default">
                                <input 
                                    type="text" 
                                    x-model="search"
                                    placeholder="Cari kategori..."
                                    class="bg-neutral-secondary-medium border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                                >
                            </div>

                            <!-- Options -->
                            <ul class="max-h-48 overflow-y-auto p-2">
                                <li>
                                    <button
                                        type="button"
                                        @click="selectCategory('', '-- Pilih Kategori --')"
                                        class="w-full text-left px-3 py-2 text-sm text-body hover:bg-neutral-secondary-medium rounded transition-colors"
                                        :class="{'bg-brand-soft text-brand': selectedValue === ''}"
                                    >
                                        -- Pilih Kategori --
                                    </button>
                                </li>
                                <?php foreach ($categories as $category): ?>
                                    <li x-show="'<?= strtolower(htmlspecialchars($category->name)) ?>'.includes(search.toLowerCase())">
                                        <button
                                            type="button"
                                            @click="selectCategory('<?= $category->id ?>', '<?= htmlspecialchars($category->name) ?>')"
                                            class="w-full text-left px-3 py-2 text-sm text-body hover:bg-neutral-secondary-medium rounded transition-colors"
                                            :class="{'bg-brand-soft text-brand': selectedValue === '<?= $category->id ?>'}"
                                        >
                                            <?= htmlspecialchars($category->name) ?>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Hidden input -->
                    <input type="hidden" name="category_id" :value="selectedValue">
                    
                    <?php if (hasError('category_id')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('category_id') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Featured Image -->
                <div>
                    <label class="block text-sm font-medium text-heading mb-2">
                        Featured Image <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    
                    <div x-show="thumbnailPreview" class="mb-3 relative">
                        <img :src="thumbnailPreview" class="w-full h-48 object-cover rounded-base border border-default">
                        <button 
                            type="button" 
                            @click="removeThumbnail()"
                            class="absolute top-2 right-2 p-1.5 bg-danger text-white rounded-full hover:bg-danger-strong shadow"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <input 
                        type="file" 
                        name="thumbnail" 
                        accept="image/*"
                        @change="previewThumbnail($event)"
                        x-ref="thumbnailInput"
                        class="block w-full text-sm text-body border border-default rounded-base cursor-pointer bg-neutral-primary focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-brand-soft file:text-brand hover:file:bg-brand-medium"
                    >
                    <p class="text-xs text-body mt-2">Format: JPG, PNG, WebP. Maksimal 5MB</p>
                    <?php if (hasError('thumbnail')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('thumbnail') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Content WYSIWYG Editor -->
                <div>
                    <label class="block text-sm font-medium text-heading mb-2">
                        Konten Blog <span class="text-danger">*</span>
                    </label>
                    <?php include view_path('components/wysiwyg-editor.php'); ?>
                    <?php if (hasError('content')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('content') ?></p>
                    <?php endif; ?>
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
                            placeholder="SEO title (kosongkan untuk gunakan judul blog)"
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

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-heading mb-2">
                        Status <span class="text-danger">*</span>
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                    >
                        <option value="draft" <?= old('status', 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= old('status') === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                    <?php if (hasError('status')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('status') ?></p>
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
                    Simpan Blog
                </button>
                <a 
                    href="<?= url('admin/blogs') ?>"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary focus:outline-none focus:ring-4 focus:ring-neutral-tertiary rounded-base transition-colors"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function blogForm() {
    return {
        title: '<?= old('title') ?>',
        slug: '<?= old('slug') ?>',
        autoSlug: true,
        thumbnailPreview: null,
        
        generateSlug() {
            if (!this.autoSlug) return;
            
            this.slug = this.title
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
        },

        previewThumbnail(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('File maksimal 5MB');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.thumbnailPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        removeThumbnail() {
            this.thumbnailPreview = null;
            this.$refs.thumbnailInput.value = '';
        }
    }
}

// Category Select Component
function categorySelect() {
    return {
        open: false,
        search: '',
        selectedValue: '<?= old('category_id') ?>',
        selectedLabel: '<?= old('category_id') ? (function() {
            foreach ($categories as $cat) {
                if ($cat->id == old('category_id')) return htmlspecialchars($cat->name);
            }
            return '-- Pilih Kategori --';
        })() : '-- Pilih Kategori --' ?>',
        
        selectCategory(value, label) {
            this.selectedValue = value;
            this.selectedLabel = label;
            this.open = false;
            this.search = '';
        }
    }
}
</script>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>