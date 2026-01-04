<!-- Services Section Partial -->
<!-- File: resources/views/partials/home/services.php -->

<?php if (!empty($services) && is_array($services)): ?>
<section class="py-12 md:py-16 lg:py-20 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-12 md:mb-16">
            <span class="text-blue-600 font-semibold mb-2 block text-sm md:text-base">Layanan Kami</span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Solusi Bisnis Terpadu</h2>
            <p class="text-gray-600 text-base md:text-lg max-w-2xl mx-auto">
                Berbagai layanan konsultasi untuk membantu bisnis Anda berkembang dan mencapai target
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <?php foreach ($services as $service): ?>
                <?php if (is_object($service)): ?>
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-blue-500 hover:shadow-lg transition-all duration-300 group">
                    <div class="mb-5">
                        <?php if (!empty($service->icon)): ?>
                            <img src="<?= htmlspecialchars(asset($service->icon)) ?>" 
                                 alt="<?= htmlspecialchars($service->title ?? '') ?>" 
                                 class="w-14 h-14 object-contain">
                        <?php else: ?>
                            <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                                <svg class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                        <?= htmlspecialchars($service->title ?? 'Layanan') ?>
                    </h3>
                    
                    <p class="text-gray-600 text-sm md:text-base mb-5 line-clamp-3 leading-relaxed">
                        <?= htmlspecialchars($service->description ?? '') ?>
                    </p>
                    
                    <a href="/services/<?= htmlspecialchars($service->slug ?? '') ?>" 
                       hx-get="/services/<?= htmlspecialchars($service->slug ?? '') ?>" 
                       hx-target="#main-content" 
                       hx-swap="innerHTML transition:true"
                       hx-push-url="true"
                       class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm md:text-base group/link">
                        Lihat Detail
                        <svg class="w-4 h-4 md:w-5 md:h-5 ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-10 md:mt-12">
            <a href="/services" 
               hx-get="/services" 
               hx-target="#main-content" 
               hx-swap="innerHTML transition:true"
               hx-push-url="true"
               class="inline-flex items-center px-6 md:px-8 py-3 md:py-4 text-sm md:text-base font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 shadow-lg transform hover:scale-105 transition-all">
                Lihat Semua Layanan
                <svg class="w-4 h-4 md:w-5 md:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>