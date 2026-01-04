<?php
$isHtmx = isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';

if (!$isHtmx) {
    $title = $title ?? ($service->title ?? 'Detail Layanan');
    ob_start();
}
?>

<!-- Breadcrumb -->
<section class="bg-white border-b border-neutral-tertiary">
    <div class="max-w-screen-xl mx-auto px-4 py-6">
        <div class="flex items-center gap-2 text-sm text-body">
            <a href="/" 
               hx-get="/"
               hx-target="#main-content"
               hx-swap="innerHTML"
               hx-push-url="true"
               class="hover:text-brand transition-colors">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="/services" 
               hx-get="/services"
               hx-target="#main-content"
               hx-swap="innerHTML"
               hx-push-url="true"
               class="hover:text-brand transition-colors">Layanan</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-heading font-medium"><?= htmlspecialchars($service->title) ?></span>
        </div>
    </div>
</section>

<!-- Service Detail -->
<section class="py-20 bg-neutral-primary">
    <div class="max-w-screen-xl mx-auto px-4">
        <!-- Hero Content -->
        <div class="grid lg:grid-cols-2 gap-12 mb-20">
            <!-- Left Column - Image -->
            <div class="animate-fade-in">
                <div class="relative rounded-2xl overflow-hidden bg-neutral-secondary aspect-video">
                    <?php if ($service->image): ?>
                        <img src="<?= htmlspecialchars(asset($service->image)) ?>" 
                             alt="<?= htmlspecialchars($service->title) ?>" 
                             class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-32 h-32 text-body/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    <?php endif; ?>

                    <?php if ($service->icon): ?>
                        <div class="absolute top-6 left-6 w-20 h-20 bg-white rounded-2xl shadow-xl flex items-center justify-center p-4">
                            <img src="/uploads/services/icons/<?= htmlspecialchars(basename($service->icon)) ?>" 
                                 alt="<?= htmlspecialchars($service->title) ?> icon" 
                                 class="w-full h-full object-contain">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column - Content -->
            <div class="animate-fade-in">
                <h1 class="text-3xl lg:text-4xl font-bold text-heading mb-6"><?= htmlspecialchars($service->title) ?></h1>
                
                <?php if ($service->description): ?>
                    <div class="text-body text-lg leading-relaxed mb-8">
                        <?= nl2br(htmlspecialchars($service->description)) ?>
                    </div>
                <?php endif; ?>

                <!-- CTA Button -->
                <div class="flex flex-wrap gap-4">
                    <a href="/contact" 
                       hx-get="/contact"
                       hx-target="#main-content"
                       hx-swap="innerHTML"
                       hx-push-url="true"
                       class="inline-flex items-center px-8 py-4 text-lg font-semibold text-white bg-brand rounded-lg hover:bg-brand-strong transition-all duration-300 hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Konsultasi Sekarang
                    </a>
                    <a href="/services" 
                       hx-get="/services"
                       hx-target="#main-content"
                       hx-swap="innerHTML"
                       hx-push-url="true"
                       class="inline-flex items-center px-8 py-4 text-lg font-semibold text-brand bg-white border-2 border-brand rounded-lg hover:bg-brand hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Layanan
                    </a>
                </div>
            </div>
        </div>

        <!-- Benefits Section (Optional - if you want to add benefits) -->
        <div class="mb-20">
            <h2 class="text-2xl lg:text-3xl font-bold text-heading mb-8 text-center">Manfaat Layanan Ini</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-6 border border-neutral-tertiary">
                    <div class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-heading mb-2">Hasil Terukur</h3>
                    <p class="text-body text-sm">Pendekatan berbasis data untuk hasil yang dapat diukur dan dioptimalkan</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-neutral-tertiary">
                    <div class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-heading mb-2">Tim Ahli</h3>
                    <p class="text-body text-sm">Konsultan berpengalaman dengan keahlian mendalam di bidangnya</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-neutral-tertiary">
                    <div class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-heading mb-2">Terpercaya</h3>
                    <p class="text-body text-sm">Track record terbukti dalam membantu klien mencapai tujuan bisnis</p>
                </div>
            </div>
        </div>

        <!-- Related Services -->
        <?php if (!empty($relatedServices)): ?>
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-heading mb-8 text-center">Layanan Lainnya</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <?php foreach ($relatedServices as $related): ?>
                        <article class="bg-white rounded-2xl overflow-hidden border border-neutral-tertiary hover:border-brand transition-all duration-300 hover:-translate-y-2 group">
                            <div class="relative h-40 overflow-hidden bg-neutral-secondary">
                                <?php if ($related->image): ?>
                                    <img src="<?= htmlspecialchars(asset($related->image)) ?>" 
                                         alt="<?= htmlspecialchars($related->title) ?>" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-body/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>

                                <?php if ($related->icon): ?>
                                    <div class="absolute top-3 left-3 w-12 h-12 bg-white rounded-lg shadow-lg flex items-center justify-center p-2">
                                        <img src="/uploads/services/icons/<?= htmlspecialchars(basename($related->icon)) ?>" 
                                             alt="<?= htmlspecialchars($related->title) ?> icon" 
                                             class="w-full h-full object-contain">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-6">
                                <h3 class="text-lg font-bold text-heading mb-2 group-hover:text-brand transition-colors">
                                    <?= htmlspecialchars($related->title) ?>
                                </h3>
                                
                                <?php if ($related->description): ?>
                                    <p class="text-body text-sm leading-relaxed mb-4 line-clamp-2">
                                        <?= htmlspecialchars($related->description) ?>
                                    </p>
                                <?php endif; ?>

                                <a href="/services/<?= htmlspecialchars($related->slug) ?>" 
                                   hx-get="/services/<?= htmlspecialchars($related->slug) ?>"
                                   hx-target="#main-content"
                                   hx-swap="innerHTML"
                                   hx-push-url="true"
                                   class="inline-flex items-center text-brand font-semibold hover:underline group/link">
                                    <span>Lihat Detail</span>
                                    <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
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