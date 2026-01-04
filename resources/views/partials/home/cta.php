<!-- File: resources/views/partials/home/cta.php -->
<section class="py-16 md:py-20 lg:py-24 bg-blue-600 relative overflow-hidden">
    <!-- Decorative background -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-white mb-4 md:mb-6">
            Siap Mengembangkan Bisnis Anda?
        </h2>
        <p class="text-base md:text-lg lg:text-xl text-white/90 mb-8 md:mb-10 max-w-2xl mx-auto leading-relaxed">
            Konsultasikan kebutuhan bisnis Anda dengan tim ahli kami dan dapatkan solusi terbaik yang disesuaikan untuk mencapai target Anda
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/contact" 
               hx-get="/contact" 
               hx-target="#main-content" 
               hx-swap="innerHTML transition:true"
               hx-push-url="true"
               class="inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 text-base md:text-lg font-semibold text-blue-600 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-white/30 shadow-lg transform hover:scale-105 transition-all">
                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Konsultasi Gratis
            </a>
            
            <?php if ($company && $company->phone): ?>
            <a href="tel:<?= htmlspecialchars($company->phone) ?>" 
               class="inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 text-base md:text-lg font-semibold text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-white/30 shadow-lg transition-all transform hover:scale-105">
                <svg class="w-5 h-5 md:w-6 md:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <?= htmlspecialchars($company->phone) ?>
            </a>
            <?php endif; ?>
        </div>

        <p class="text-white/80 text-sm md:text-base mt-6 md:mt-8">
            <svg class="w-4 h-4 md:w-5 md:h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Respon cepat dalam 24 jam • Konsultasi pertama gratis • Tanpa komitmen
        </p>
    </div>
</section>