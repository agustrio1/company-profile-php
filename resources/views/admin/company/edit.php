<?php
$title = 'Company Profile';
ob_start();
?>

<div class="max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-heading">Company Profile</h1>
        <p class="text-sm text-body mt-1">Kelola informasi profil perusahaan</p>
    </div>

    <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
        <form action="<?= url('admin/company') ?>" method="POST" enctype="multipart/form-data" x-data="companyForm()">
            <?= csrf_field() ?>
            <?= method_field('PUT') ?>

            <div class="space-y-5">
                <!-- Basic Information -->
                <div class="border-b border-default pb-5">
                    <h3 class="text-lg font-semibold text-heading mb-4">Informasi Dasar</h3>
                    
                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-heading mb-2">
                            Nama Perusahaan <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?= old('name', $company->name ?? '') ?>"
                            required
                            @input="generateSlug()"
                            x-model="name"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="Masukkan nama perusahaan..."
                        >
                        <?php if (hasError('name')): ?>
                            <p class="mt-1 text-sm text-danger"><?= error('name') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Slug -->
                    <div class="mb-4">
                        <label for="slug" class="block text-sm font-medium text-heading mb-2">
                            Slug <span class="text-danger">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                id="slug" 
                                name="slug" 
                                value="<?= old('slug', $company->slug ?? '') ?>"
                                required
                                x-model="slug"
                                :readonly="autoSlug"
                                class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                                placeholder="nama-perusahaan"
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
                        <?php if (hasError('slug')): ?>
                            <p class="mt-1 text-sm text-danger"><?= error('slug') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-heading mb-2">
                            Logo Perusahaan
                        </label>
                        
                        <?php if (!empty($company->logo ?? null)): ?>
                            <?php
                            $logoPath = $company->logo;
                            if (strpos($logoPath, 'uploads/') !== 0) {
                                $logoPath = 'uploads/' . ltrim($logoPath, '/');
                            }
                            ?>
                            <div class="mb-3">
                                <p class="text-xs text-body mb-2">Logo saat ini:</p>
                                <img src="<?= asset($logoPath) ?>" alt="Company logo" class="h-20 object-contain border border-default rounded p-2">
                            </div>
                        <?php endif; ?>
                        
                        <div x-show="logoPreview" class="mb-3 relative inline-block">
                            <p class="text-xs text-body mb-2">Preview logo baru:</p>
                            <img :src="logoPreview" class="h-20 object-contain border border-default rounded p-2">
                            <button 
                                type="button" 
                                @click="removeLogo()"
                                class="absolute -top-2 -right-2 p-1.5 bg-danger text-white rounded-full hover:bg-danger-strong shadow"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        
                        <input 
                            type="file" 
                            name="logo" 
                            accept="image/*"
                            @change="previewLogo($event)"
                            x-ref="logoInput"
                            class="block w-full text-sm text-body border border-default rounded-base cursor-pointer bg-neutral-primary focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-brand-soft file:text-brand hover:file:bg-brand-medium"
                        >
                        <p class="text-xs text-body mt-2">Format: PNG, SVG, JPG. Maksimal 5MB</p>
                        <?php if (hasError('logo')): ?>
                            <p class="mt-1 text-sm text-danger"><?= error('logo') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description -->
                <div class="border-b border-default pb-5">
                    <h3 class="text-lg font-semibold text-heading mb-4">Deskripsi</h3>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-heading mb-2">
                            Tentang Perusahaan
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="Deskripsi singkat tentang perusahaan..."
                        ><?= old('description', $company->description ?? '') ?></textarea>
                        <?php if (hasError('description')): ?>
                            <p class="mt-1 text-sm text-danger"><?= error('description') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="vision" class="block text-sm font-medium text-heading mb-2">
                            Visi
                        </label>
                        <textarea 
                            id="vision" 
                            name="vision" 
                            rows="3"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="Visi perusahaan..."
                        ><?= old('vision', $company->vision ?? '') ?></textarea>
                        <?php if (hasError('vision')): ?>
                            <p class="mt-1 text-sm text-danger"><?= error('vision') ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="mission" class="block text-sm font-medium text-heading mb-2">
                            Misi
                        </label>
                        <textarea 
                            id="mission" 
                            name="mission" 
                            rows="3"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="Misi perusahaan..."
                        ><?= old('mission', $company->mission ?? '') ?></textarea>
                        <?php if (hasError('mission')): ?>
                            <p class="mt-1 text-sm text-danger"><?= error('mission') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="border-b border-default pb-5">
                    <h3 class="text-lg font-semibold text-heading mb-4">Informasi Kontak</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-heading mb-2">
                                Telepon
                            </label>
                            <input 
                                type="text" 
                                id="phone" 
                                name="phone" 
                                value="<?= old('phone', $company->phone ?? '') ?>"
                                class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                                placeholder="+62 xxx-xxx-xxxx"
                            >
                            <?php if (hasError('phone')): ?>
                                <p class="mt-1 text-sm text-danger"><?= error('phone') ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-heading mb-2">
                                Email
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="<?= old('email', $company->email ?? '') ?>"
                                class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                                placeholder="info@company.com"
                            >
                            <?php if (hasError('email')): ?>
                                <p class="mt-1 text-sm text-danger"><?= error('email') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="website" class="block text-sm font-medium text-heading mb-2">
                            Website
                        </label>
                        <input 
                            type="url" 
                            id="website" 
                            name="website" 
                            value="<?= old('website', $company->website ?? '') ?>"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="https://company.com"
                        >
                        <?php if (hasError('website')): ?>
                            <p class="mt-1 text-sm text-danger"><?= error('website') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-heading mb-2">
                            Alamat
                        </label>
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="3"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="Alamat lengkap perusahaan..."
                        ><?= old('address', $company->address ?? '') ?></textarea>
                        <?php if (hasError('address')): ?>
                            <p class="mt-1 text-sm text-danger"><?= error('address') ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="founded_year" class="block text-sm font-medium text-heading mb-2">
                            Tahun Berdiri
                        </label>
                        <input 
                            type="number" 
                            id="founded_year" 
                            name="founded_year" 
                            value="<?= old('founded_year', $company->founded_year ?? '') ?>"
                            min="1900"
                            max="<?= date('Y') ?>"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="<?= date('Y') ?>"
                        >
                        <?php if (hasError('founded_year')): ?>
                            <p class="mt-1 text-sm text-danger"><?= error('founded_year') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- SEO Section -->
                <div>
                    <h3 class="text-lg font-semibold text-heading mb-4">SEO Optimization</h3>
                    
                    <div class="mb-4">
                        <label for="seo_title" class="block text-sm font-medium text-heading mb-2">
                            Meta Title
                        </label>
                        <input 
                            type="text" 
                            id="seo_title" 
                            name="seo[title]" 
                            value="<?= old('seo.title', $company->seo->title ?? '') ?>"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="SEO title..."
                        >
                        <p class="mt-1 text-xs text-body">Optimal: 50-60 karakter</p>
                    </div>

                    <div class="mb-4">
                        <label for="seo_description" class="block text-sm font-medium text-heading mb-2">
                            Meta Description
                        </label>
                        <textarea 
                            id="seo_description" 
                            name="seo[description]" 
                            rows="3"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                            placeholder="Deskripsi untuk mesin pencari..."
                        ><?= old('seo.description', $company->seo->description ?? '') ?></textarea>
                        <p class="mt-1 text-xs text-body">Optimal: 150-160 karakter</p>
                    </div>

                    <div>
                        <label for="seo_keywords" class="block text-sm font-medium text-heading mb-2">
                            Meta Keywords
                        </label>
                        <input 
                            type="text" 
                            id="seo_keywords" 
                            name="seo[keywords]" 
                            value="<?= old('seo.keywords', $company->seo->keywords ?? '') ?>"
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
                    Simpan Profile
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function companyForm() {
    return {
        name: '<?= old('name', addslashes($company->name ?? '')) ?>',
        slug: '<?= old('slug', addslashes($company->slug ?? '')) ?>',
        autoSlug: <?= empty($company) ? 'true' : 'false' ?>,
        logoPreview: null,
        
        generateSlug() {
            if (!this.autoSlug) return;
            
            this.slug = this.name
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
        },

        previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('File maksimal 5MB');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.logoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        removeLogo() {
            this.logoPreview = null;
            this.$refs.logoInput.value = '';
        }
    }
}
</script>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>