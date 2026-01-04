<!-- Hero Section -->
<section class="relative" style="height: 450px;">
    <div class="absolute inset-0">
        <img src="<?= htmlspecialchars(asset('images/meeting.webp')) ?>" 
             alt="<?= htmlspecialchars($company->name ?? '') ?>" 
             class="w-full h-full object-cover"
             onerror="this.style.display='none';">
        <div class="absolute inset-0" style="background-color: rgba(0, 0, 0, 0.45);"></div>
    </div>
    
    <div class="relative h-full flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-xl">
                <?php if ($company && $company->founded_year): ?>
                <p class="text-white mb-3 sm:mb-4" style="font-size: 11px; letter-spacing: 2px; font-weight: 500; opacity: 0.9;">
                    SEJAK <?= htmlspecialchars($company->founded_year) ?>
                </p>
                <?php endif; ?>
                
                <h1 class="text-white mb-4 sm:mb-5" style="font-size: 32px; font-weight: 700; line-height: 1.2;">
                    <?= htmlspecialchars($company->name ?? 'Company Name') ?>
                </h1>
                
                <p class="text-white mb-6 sm:mb-8" style="font-size: 16px; line-height: 1.6; opacity: 0.95;">
                    <?= htmlspecialchars($company->description ?? 'Solusi konsultan bisnis profesional untuk pertumbuhan perusahaan Anda') ?>
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="/services" 
                       hx-get="/services" 
                       hx-target="#main-content" 
                       hx-swap="innerHTML transition:true"
                       hx-push-url="true"
                       class="text-center bg-white text-blue-600 hover:bg-gray-100"
                       style="padding: 12px 28px; font-weight: 600; font-size: 14px; transition: background-color 0.2s;">
                        Layanan Kami
                    </a>
                    <a href="/contact" 
                       hx-get="/contact" 
                       hx-target="#main-content" 
                       hx-swap="innerHTML transition:true"
                       hx-push-url="true"
                       class="text-center border border-white text-white hover:bg-white/10"
                       style="padding: 12px 28px; font-weight: 600; font-size: 14px; transition: all 0.2s;">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="bg-white py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-10">
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-bold text-blue-600 mb-1 sm:mb-2">
                    <?= $company->founded_year ? (date('Y') - $company->founded_year) : 15 ?>+
                </div>
                <div class="text-xs sm:text-sm text-gray-600 uppercase tracking-wider">
                    Tahun Pengalaman
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-bold text-blue-600 mb-1 sm:mb-2">50+</div>
                <div class="text-xs sm:text-sm text-gray-600 uppercase tracking-wider">
                    Klien Aktif
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-bold text-blue-600 mb-1 sm:mb-2">1000+</div>
                <div class="text-xs sm:text-sm text-gray-600 uppercase tracking-wider">
                    Proyek Selesai
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-bold text-blue-600 mb-1 sm:mb-2">24/7</div>
                <div class="text-xs sm:text-sm text-gray-600 uppercase tracking-wider">
                    Dukungan
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 sm:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 sm:mb-12">
            <p class="text-xs text-blue-600 uppercase tracking-widest font-semibold mb-2">KEUNGGULAN</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">
                Mengapa Memilih Kami
            </h2>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 sm:gap-10">
            <div>
                <div class="mb-4">
                    <svg class="w-9 h-9 sm:w-10 sm:h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                    Tim Profesional
                </h3>
                <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
                    Konsultan berpengalaman dengan keahlian mendalam di berbagai sektor industri
                </p>
            </div>
            
            <div>
                <div class="mb-4">
                    <svg class="w-9 h-9 sm:w-10 sm:h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                    Strategi Terbukti
                </h3>
                <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
                    Pendekatan berbasis data dan analisis mendalam untuk hasil maksimal
                </p>
            </div>
            
            <div>
                <div class="mb-4">
                    <svg class="w-9 h-9 sm:w-10 sm:h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                    Hasil Terukur
                </h3>
                <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
                    Fokus pada pencapaian target dengan metrik yang jelas dan transparan
                </p>
            </div>
        </div>
    </div>
</section>

<style>
@media (min-width: 640px) {
    section:first-child {
        height: 550px !important;
    }
    section:first-child h1 {
        font-size: 48px !important;
    }
    section:first-child p:nth-of-type(2) {
        font-size: 18px !important;
    }
}
</style>