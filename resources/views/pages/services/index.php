<?php
$isHtmx = isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';

if (!$isHtmx) {
    $title = $title ?? 'Layanan Konsultan Bisnis';
    ob_start();
}
?>

<!-- Hero Section -->
<section class="bg-white border-b border-neutral-tertiary">
    <div class="max-w-screen-xl mx-auto px-4 py-16 animate-fade-in">
        <div class="text-center">
            <h1 class="text-4xl lg:text-5xl font-bold text-heading mb-4">Layanan Konsultan Bisnis</h1>
            <p class="text-xl text-body max-w-2xl mx-auto">Solusi lengkap untuk mengembangkan dan mengoptimalkan strategi bisnis Anda dengan pendekatan profesional dan terukur</p>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-20 bg-neutral-primary">
    <div class="max-w-screen-xl mx-auto px-4">
        <?php if (!empty($services)): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($services as $service): ?>
                    <article class="bg-white rounded-2xl overflow-hidden border border-neutral-tertiary hover:border-brand transition-all duration-300 hover:-translate-y-2 group">
                        <!-- Service Image -->
                        <div class="relative h-48 overflow-hidden bg-neutral-secondary">
                            <?php if ($service->image): ?>
                                <img src="<?= htmlspecialchars(asset($service->image)) ?>" 
                                     alt="<?= htmlspecialchars($service->title) ?>" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-20 h-20 text-body/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Icon Overlay -->
                            <?php if ($service->icon): ?>
                                <div class="absolute top-4 left-4 w-16 h-16 bg-white rounded-xl shadow-lg flex items-center justify-center p-3">
                                    <img src="/uploads/services/icons/<?= htmlspecialchars(basename($service->icon)) ?>" 
                                         alt="<?= htmlspecialchars($service->title) ?> icon" 
                                         class="w-full h-full object-contain">
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Service Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-heading mb-3 group-hover:text-brand transition-colors">
                                <?= htmlspecialchars($service->title) ?>
                            </h3>
                            
                            <?php if ($service->description): ?>
                                <p class="text-body text-sm leading-relaxed mb-4 line-clamp-3">
                                    <?= htmlspecialchars($service->description) ?>
                                </p>
                            <?php endif; ?>

                            <!-- CTA Button -->
                            <a href="/services/<?= htmlspecialchars($service->slug) ?>" 
                               hx-get="/services/<?= htmlspecialchars($service->slug) ?>"
                               hx-target="#main-content"
                               hx-swap="innerHTML"
                               hx-push-url="true"
                               class="inline-flex items-center justify-center w-full px-6 py-3 text-brand bg-brand/10 rounded-lg hover:bg-brand hover:text-white transition-all duration-300 font-semibold group/btn">
                                <span>Lihat Detail</span>
                                <svg class="w-5 h-5 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-body/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="text-2xl font-bold text-heading mb-2">Belum Ada Layanan</h3>
                <p class="text-body">Layanan konsultasi akan segera hadir untuk melayani kebutuhan bisnis Anda</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Why Choose Our Services -->
<section class="py-20 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="text-3xl lg:text-4xl font-bold text-heading mb-4">Mengapa Memilih Layanan Kami?</h2>
            <p class="text-body text-lg max-w-2xl mx-auto">Komitmen kami untuk memberikan solusi konsultasi bisnis terbaik dengan pendekatan profesional</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-brand/10 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-brand transition-colors duration-300">
                    <svg class="w-8 h-8 text-brand group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-heading mb-3">Konsultan Berpengalaman</h3>
                <p class="text-body leading-relaxed">Tim konsultan profesional dengan track record terbukti di berbagai industri</p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-brand/10 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-brand transition-colors duration-300">
                    <svg class="w-8 h-8 text-brand group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-heading mb-3">Pendekatan Data-Driven</h3>
                <p class="text-body leading-relaxed">Solusi berbasis data dan analisis mendalam untuk hasil yang terukur</p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-brand/10 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-brand transition-colors duration-300">
                    <svg class="w-8 h-8 text-brand group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-heading mb-3">Support Berkelanjutan</h3>
                <p class="text-body leading-relaxed">Pendampingan penuh untuk memastikan implementasi strategi yang sukses</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-neutral-primary border-t border-neutral-tertiary">
    <div class="max-w-4xl mx-auto px-4 text-center animate-fade-in">
        <h2 class="text-3xl lg:text-4xl font-bold text-heading mb-4">Siap Mengembangkan Bisnis Anda?</h2>
        <p class="text-xl text-body mb-10">Konsultasikan kebutuhan bisnis Anda dengan tim ahli kami dan dapatkan solusi terbaik</p>
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
                Konsultasi Gratis
            </a>
            <a href="/about" 
               hx-get="/about"
               hx-target="#main-content"
               hx-swap="innerHTML"
               hx-push-url="true"
               class="inline-flex items-center px-8 py-4 text-lg font-semibold text-brand bg-white border-2 border-brand rounded-lg hover:bg-brand hover:text-white transition-all duration-300 hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tentang Kami
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