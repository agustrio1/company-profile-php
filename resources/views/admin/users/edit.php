<?php
$title = 'Edit Kategori Blog';
ob_start();
?>

<div class="max-w-3xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-body mb-2">
            <a href="<?= url('admin/blog-categories') ?>" class="hover:text-fg-brand">Kategori Blog</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-heading">Edit Kategori</span>
        </div>
        <h1 class="text-2xl font-bold text-heading">Edit Kategori Blog</h1>
        <p class="text-sm text-body mt-1">Update informasi kategori blog</p>
    </div>

    <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
        <form action="<?= url('admin/blog-categories/' . $category->id) ?>" method="POST" x-data="categoryForm()">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">

            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-heading mb-2">
                        Nama Kategori <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?= old('name', $category->name) ?>"
                        required
                        @input="generateSlug()"
                        x-model="name"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Teknologi"
                    >
                    <?php if (hasError('name')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('name') ?></p>
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
                            value="<?= old('slug', $category->slug) ?>"
                            required
                            x-model="slug"
                            :readonly="autoSlug"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="teknologi"
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
                        <span x-show="autoSlug">Slug otomatis dibuat dari nama kategori</span>
                        <span x-show="!autoSlug">Mode manual - edit slug sesuai kebutuhan</span>
                    </p>
                    <?php if (hasError('slug')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('slug') ?></p>
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
                        placeholder="Deskripsi singkat tentang kategori ini..."
                    ><?= old('description', $category->description) ?></textarea>
                    <?php if (hasError('description')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('description') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Category Info -->
                <div class="bg-neutral-secondary-medium rounded-base p-4">
                    <h3 class="text-sm font-medium text-heading mb-2">Informasi Kategori</h3>
                    <dl class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <dt class="text-body">Kategori ID</dt>
                            <dd class="text-heading font-mono text-xs"><?= htmlspecialchars($category->id) ?></dd>
                        </div>
                        <div>
                            <dt class="text-body">Slug Saat Ini</dt>
                            <dd class="text-heading">
                                <code class="text-xs bg-neutral-primary px-2 py-1 rounded"><?= htmlspecialchars($category->slug) ?></code>
                            </dd>
                        </div>
                    </dl>
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
                    Update Kategori
                </button>
                <a 
                    href="<?= url('admin/blog-categories') ?>"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary focus:outline-none focus:ring-4 focus:ring-neutral-tertiary rounded-base transition-colors"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function categoryForm() {
    return {
        name: '<?= old('name', $category->name) ?>',
        slug: '<?= old('slug', $category->slug) ?>',
        autoSlug: false, // Default false di edit mode
        
        generateSlug() {
            if (!this.autoSlug) return;
            
            this.slug = this.name
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
        }
    }
}
</script>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>