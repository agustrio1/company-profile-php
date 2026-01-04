<?php
$isHtmx = isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';

if (!$isHtmx) {
    $title = $title ?? 'Hubungi Kami';
    ob_start();
}
?>

<!-- Hero Section -->
<section class="bg-white border-b border-neutral-tertiary">
    <div class="max-w-screen-xl mx-auto px-4 py-16 animate-fade-in">
        <div class="text-center">
            <h1 class="text-4xl lg:text-5xl font-bold text-heading mb-4">Hubungi Kami</h1>
            <p class="text-xl text-body max-w-2xl mx-auto">Kami siap membantu Anda. Hubungi kami untuk konsultasi atau pertanyaan seputar layanan kami</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-neutral-primary">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            <!-- Address -->
            <?php if ($company->address): ?>
                <div class="bg-white border-2 border-neutral-tertiary rounded-2xl p-8 hover:border-brand transition-all duration-300 hover:-translate-y-2 group text-center">
                    <div class="w-16 h-16 bg-brand/10 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-brand transition-colors">
                        <svg class="w-8 h-8 text-brand group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-heading mb-3">Alamat</h3>
                    <p class="text-body leading-relaxed"><?= nl2br(htmlspecialchars($company->address)) ?></p>
                </div>
            <?php endif; ?>

            <!-- Email -->
            <?php if ($company->email): ?>
                <div class="bg-white border-2 border-neutral-tertiary rounded-2xl p-8 hover:border-brand transition-all duration-300 hover:-translate-y-2 group text-center">
                    <div class="w-16 h-16 bg-brand/10 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-brand transition-colors">
                        <svg class="w-8 h-8 text-brand group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-heading mb-3">Email</h3>
                    <a href="mailto:<?= htmlspecialchars($company->email) ?>" class="text-brand hover:underline text-lg font-semibold">
                        <?= htmlspecialchars($company->email) ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Phone -->
            <?php if ($company->phone): ?>
                <div class="bg-white border-2 border-neutral-tertiary rounded-2xl p-8 hover:border-brand transition-all duration-300 hover:-translate-y-2 group text-center">
                    <div class="w-16 h-16 bg-brand/10 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-brand transition-colors">
                        <svg class="w-8 h-8 text-brand group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-heading mb-3">Telepon</h3>
                    <a href="tel:<?= htmlspecialchars($company->phone) ?>" class="text-brand hover:underline text-lg font-semibold">
                        <?= htmlspecialchars($company->phone) ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Website -->
            <?php if ($company->website): ?>
                <div class="bg-white border-2 border-neutral-tertiary rounded-2xl p-8 hover:border-brand transition-all duration-300 hover:-translate-y-2 group text-center">
                    <div class="w-16 h-16 bg-brand/10 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-brand transition-colors">
                        <svg class="w-8 h-8 text-brand group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-heading mb-3">Website</h3>
                    <a href="<?= htmlspecialchars($company->website) ?>" target="_blank" class="text-brand hover:underline text-lg font-semibold break-all">
                        <?= htmlspecialchars($company->website) ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Additional Info -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Business Hours -->
            <div class="bg-white border-2 border-neutral-tertiary rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-heading mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Jam Operasional
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-3 border-b border-neutral-tertiary">
                        <span class="font-medium text-body">Senin - Jumat</span>
                        <span class="font-bold text-heading">08:00 - 17:00</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-neutral-tertiary">
                        <span class="font-medium text-body">Sabtu</span>
                        <span class="font-bold text-heading">08:00 - 12:00</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="font-medium text-body">Minggu</span>
                        <span class="font-bold text-red-600">Tutup</span>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-white border-2 border-neutral-tertiary rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-heading mb-6">Ikuti Kami</h3>
                <p class="text-body mb-6">Dapatkan update terbaru dari kami melalui media sosial</p>
                <div class="flex flex-wrap gap-3">
                    <a href="#" class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center hover:bg-brand hover:text-white transition-all duration-300 hover:scale-110" title="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center hover:bg-brand hover:text-white transition-all duration-300 hover:scale-110" title="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center hover:bg-brand hover:text-white transition-all duration-300 hover:scale-110" title="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center hover:bg-brand hover:text-white transition-all duration-300 hover:scale-110" title="LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Contact -->
            <div class="bg-white border-2 border-neutral-tertiary rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-heading mb-6">Hubungi Cepat</h3>
                <p class="text-body mb-6">Butuh bantuan segera? Hubungi kami melalui:</p>
                <div class="space-y-4">
                    <?php if ($company->phone): ?>
                        <a href="tel:<?= htmlspecialchars($company->phone) ?>" class="flex items-center gap-3 p-3 bg-brand/10 rounded-xl hover:bg-brand hover:text-white transition-all duration-300 group">
                            <svg class="w-5 h-5 text-brand group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="font-semibold">Telepon Sekarang</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($company->email): ?>
                        <a href="mailto:<?= htmlspecialchars($company->email) ?>" class="flex items-center gap-3 p-3 bg-brand/10 rounded-xl hover:bg-brand hover:text-white transition-all duration-300 group">
                            <svg class="w-5 h-5 text-brand group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-semibold">Kirim Email</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
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