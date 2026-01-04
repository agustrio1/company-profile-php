<?php
$isHtmx = isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';

if (!$isHtmx) {
    $title = $company->name ?? 'Tentang Kami';
    ob_start();
}
?>

<!-- Hero Section -->
<section class="bg-white border-b border-neutral-tertiary">
    <div class="max-w-screen-xl mx-auto px-4 py-16 animate-fade-in">
        <div class="text-center">
            <h1 class="text-4xl lg:text-5xl font-bold text-heading mb-4">Tentang Kami</h1>
            <p class="text-xl text-body">Mengenal lebih dekat <?= htmlspecialchars($company->name ?? 'perusahaan kami') ?></p>
        </div>
    </div>
</section>

<!-- Company Profile -->
<section class="py-20 bg-neutral-primary">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="order-2 lg:order-1 animate-fade-in">
                <h2 class="text-3xl lg:text-4xl font-bold text-heading mb-6"><?= htmlspecialchars($company->name ?? '') ?></h2>
                <?php if ($company->description): ?>
                    <div class="text-body text-lg mb-8 leading-relaxed">
                        <?= nl2br(htmlspecialchars($company->description)) ?>
                    </div>
                <?php endif; ?>
                
                <div class="space-y-4">
                    <?php if ($company->founded_year): ?>
                        <div class="flex items-start gap-4 hover:translate-x-2 transition-transform duration-300">
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-body font-medium">Didirikan Tahun</p>
                                <p class="text-lg font-semibold text-heading"><?= htmlspecialchars($company->founded_year) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($company->email): ?>
                        <div class="flex items-start gap-4 hover:translate-x-2 transition-transform duration-300">
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-body font-medium">Email</p>
                                <a href="mailto:<?= htmlspecialchars($company->email) ?>" class="text-lg font-semibold text-fg-brand hover:underline"><?= htmlspecialchars($company->email) ?></a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($company->phone): ?>
                        <div class="flex items-start gap-4 hover:translate-x-2 transition-transform duration-300">
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-body font-medium">Telepon</p>
                                <a href="tel:<?= htmlspecialchars($company->phone) ?>" class="text-lg font-semibold text-fg-brand hover:underline"><?= htmlspecialchars($company->phone) ?></a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($company->website): ?>
                        <div class="flex items-start gap-4 hover:translate-x-2 transition-transform duration-300">
                            <div class="w-12 h-12 bg-brand/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-body font-medium">Website</p>
                                <a href="<?= htmlspecialchars($company->website) ?>" target="_blank" class="text-lg font-semibold text-fg-brand hover:underline"><?= htmlspecialchars($company->website) ?></a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="order-1 lg:order-2 animate-fade-in">
                <?php if ($company->logo): ?>
                    <img src="<?= htmlspecialchars($company->getLogoUrl() ?? '/images/logo.svg') ?>" alt="<?= htmlspecialchars($company->name ?? '') ?>" class="w-full h-auto rounded-2xl hover:scale-105 transition-transform duration-500">
                <?php else: ?>
                    <div class="w-full h-96 bg-neutral-secondary rounded-2xl flex items-center justify-center">
                        <svg class="w-36 h-36 text-body/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<?php if ($company->vision || $company->mission): ?>
<section class="py-20 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="text-3xl lg:text-4xl font-bold text-heading mb-4">Visi & Misi</h2>
            <p class="text-body text-lg">Landasan kami dalam memberikan layanan terbaik</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            <?php if ($company->vision): ?>
                <div class="bg-white border-2 border-neutral-tertiary rounded-2xl p-8 hover:border-brand transition-all duration-300 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-brand/10 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-heading mb-4">Visi Kami</h3>
                    <div class="text-body leading-relaxed text-lg">
                        <?= nl2br(htmlspecialchars($company->vision)) ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($company->mission): ?>
                <div class="bg-white border-2 border-neutral-tertiary rounded-2xl p-8 hover:border-brand transition-all duration-300 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-brand/10 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-heading mb-4">Misi Kami</h3>
                    <div class="text-body leading-relaxed text-lg">
                        <?= nl2br(htmlspecialchars($company->mission)) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Team Section -->
<?php if (!empty($teams) && is_array($teams)): ?>
<section class="py-20 bg-neutral-primary">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="text-3xl lg:text-4xl font-bold text-heading mb-4">Tim Kami</h2>
            <p class="text-body text-lg">Kenali orang-orang hebat di balik kesuksesan kami</p>
        </div>
        
        <?php foreach ($teams as $team): ?>
            <?php if (is_object($team) && isset($team->employees) && is_array($team->employees) && !empty($team->employees)): ?>
                <div class="mb-20 last:mb-0">
                    <div class="text-center mb-12">
                        <h3 class="text-2xl lg:text-3xl font-bold text-heading mb-3"><?= htmlspecialchars($team->name ?? '') ?></h3>
                        <?php if (isset($team->description) && $team->description): ?>
                            <p class="text-body text-lg"><?= htmlspecialchars($team->description) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        <?php foreach ($team->employees as $employeeData): ?>
                            <?php 
                            // Convert array to Employee object if needed
                            if (is_array($employeeData)) {
                                $employee = new \App\Models\Employee($employeeData);
                            } else {
                                $employee = $employeeData;
                            }
                            ?>
                            <?php if ($employee && ($employee->name ?? null)): ?>
                                <div class="bg-white rounded-2xl p-6 border border-neutral-tertiary hover:border-brand transition-all duration-300 hover:-translate-y-2 group text-center">
                                    <div class="relative w-24 h-24 mx-auto mb-4">
                                        <?php if ($employee->photo): ?>
                                            <img src="<?= htmlspecialchars($employee->getPhotoUrl() ?? '/images/default-avatar.png') ?>" alt="<?= htmlspecialchars($employee->name) ?>" class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-neutral-secondary rounded-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-body/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-heading mb-1"><?= htmlspecialchars($employee->name) ?></h4>
                                        <?php if ($employee->position): ?>
                                            <p class="text-brand text-sm font-semibold mb-3"><?= htmlspecialchars($employee->position) ?></p>
                                        <?php endif; ?>
                                        <?php if ($employee->bio): ?>
                                            <p class="text-body text-sm leading-relaxed line-clamp-3"><?= htmlspecialchars($employee->bio) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-20 bg-white border-t border-neutral-tertiary">
    <div class="max-w-4xl mx-auto px-4 text-center animate-fade-in">
        <h2 class="text-3xl lg:text-4xl font-bold text-heading mb-4">Tertarik Bekerja Sama?</h2>
        <p class="text-xl text-body mb-10">Mari wujudkan proyek impian Anda bersama kami</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/contact" 
               hx-get="/contact"
               hx-target="#main-content"
               hx-swap="innerHTML"
               hx-push-url="true"
               class="inline-flex items-center px-8 py-4 text-lg font-semibold text-white bg-brand rounded-lg hover:bg-brand-strong transition-all duration-300 hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Hubungi Kami
            </a>
            <a href="/services" 
               hx-get="/services"
               hx-target="#main-content"
               hx-swap="innerHTML"
               hx-push-url="true"
               class="inline-flex items-center px-8 py-4 text-lg font-semibold text-brand bg-white border-2 border-brand rounded-lg hover:bg-brand hover:text-white transition-all duration-300 hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Lihat Layanan
            </a>
        </div>
    </div>
</section>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}
</style>

<?php
if (!$isHtmx) {
    $content = ob_get_clean();
    require view_path('layouts/app.php');
}
?>