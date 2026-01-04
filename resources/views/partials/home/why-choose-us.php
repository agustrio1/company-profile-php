<!-- File: resources/views/partials/home/why-choose-us.php -->
<section class="py-12 md:py-16 lg:py-20 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-12 md:mb-16">
            <span class="text-blue-600 font-semibold mb-2 block text-sm md:text-base">Keunggulan Kami</span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Mengapa Memilih Kami?</h2>
            <p class="text-gray-600 text-base md:text-lg max-w-2xl mx-auto">
                Kami berkomitmen memberikan solusi terbaik dengan pendekatan profesional dan hasil yang terukur
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center group">
                <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-blue-100 rounded-full mb-5 group-hover:bg-blue-600 transition-colors">
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-3">Terpercaya & Berpengalaman</h3>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    Lebih dari <?= $company->founded_year ? (date('Y') - $company->founded_year) : '15' ?> tahun melayani berbagai klien dengan track record yang terbukti
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center group">
                <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-blue-100 rounded-full mb-5 group-hover:bg-blue-600 transition-colors">
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-3">Solusi Cepat & Efektif</h3>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    Metode kerja yang efisien dan fokus pada hasil untuk mencapai target bisnis Anda lebih cepat
                </p>
            </div>

            <!-- Feature 3 - Rupiah Icon -->
            <div class="text-center group">
                <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-blue-100 rounded-full mb-5 group-hover:bg-blue-600 transition-colors">
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 76.991 76.992">
                        <g>
                            <g>
                                <path d="M46.387,75.839h-5.812c-0.639,0-1.24-0.248-1.692-0.697c-0.458-0.463-0.707-1.063-0.707-1.701l0.016-51.524 c0-0.64,0.25-1.243,0.703-1.696c0.456-0.454,1.058-0.702,1.696-0.702l5.604,0.008c1.32,0.005,2.394,1.079,2.396,2.394v0.048 c2.803-2.202,6.19-3.28,10.262-3.28c10.512,0,18.14,8.825,18.14,20.983c0,15.145-9.986,22.042-19.265,22.042 c-3.352,0-6.428-0.868-8.94-2.491v14.219C48.786,74.763,47.71,75.839,46.387,75.839z M41.176,72.839h4.61V56.038 c0-0.615,0.375-1.167,0.946-1.396c0.572-0.227,1.225-0.082,1.646,0.367c2.247,2.387,5.566,3.702,9.349,3.702 c7.834,0,16.265-5.959,16.265-19.042c0-10.42-6.367-17.983-15.14-17.983c-4.492,0-7.957,1.571-10.588,4.803 c-0.398,0.492-1.063,0.68-1.664,0.467c-0.597-0.211-0.998-0.775-1-1.409l-0.008-3.023l-4.4-0.006L41.176,72.839z M57.816,54.72 c-6.789,0-12.313-6.51-12.313-14.51s5.524-14.509,12.313-14.509c6.791,0,12.316,6.509,12.316,14.509S64.607,54.72,57.816,54.72z M57.816,28.702c-5.135,0-9.313,5.163-9.313,11.509s4.179,11.51,9.313,11.51c5.138,0,9.316-5.164,9.316-11.51 S62.954,28.702,57.816,28.702z"/>
                                <path d="M34.844,56.259H28.25c-1.124,0-2.137-0.709-2.52-1.768l-7.107-19.626h-6.889v18.713c0,1.478-1.202,2.681-2.68,2.681 H2.681C1.203,56.259,0,55.056,0,53.579V3.873c0-1.475,1.199-2.677,2.673-2.681l12.233-0.04c7.523,0,12.485,1.457,16.095,4.722 c3.068,2.707,4.765,6.748,4.765,11.365c0,6.011-1.837,10.229-6.297,14.32l7.885,21.082c0.305,0.825,0.19,1.744-0.305,2.461 C36.543,55.829,35.72,56.259,34.844,56.259z M28.474,53.259h5.909l-8.084-21.615c-0.221-0.59-0.049-1.254,0.429-1.665 c4.402-3.772,6.039-7.226,6.039-12.741c0-3.744-1.336-6.986-3.764-9.128c-3.031-2.742-7.373-3.959-14.091-3.959L3.001,4.19 v49.069h5.733V33.366c0-0.829,0.671-1.5,1.5-1.5h9.441c0.631,0,1.195,0.396,1.41,0.989L28.474,53.259z M15.575,27.669h-5.341 c-0.829,0-1.5-0.671-1.5-1.5V9.927c0-0.828,0.67-1.499,1.498-1.5l5.117-0.006c0.004-0.001,0.012,0,0.019,0 c9.64,0.107,11.664,5.253,11.664,9.552C27.031,23.772,22.427,27.669,15.575,27.669z M11.734,24.669h3.841 c5.216,0,8.456-2.566,8.456-6.697c0-2.77-0.9-6.462-8.688-6.552l-3.609,0.004V24.669z"/>
                            </g>
                        </g>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-3">Harga Kompetitif</h3>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    Investasi yang tepat dengan ROI optimal dan paket yang disesuaikan dengan budget Anda
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="text-center group">
                <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-blue-100 rounded-full mb-5 group-hover:bg-blue-600 transition-colors">
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-3">Tim Profesional</h3>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    Konsultan bersertifikasi dan berpengalaman di berbagai industri siap membantu Anda
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="text-center group">
                <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-blue-100 rounded-full mb-5 group-hover:bg-blue-600 transition-colors">
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-3">Data-Driven Approach</h3>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    Keputusan berdasarkan data dan analisis mendalam untuk hasil yang akurat dan terukur
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="text-center group">
                <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-blue-100 rounded-full mb-5 group-hover:bg-blue-600 transition-colors">
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-3">Support Berkelanjutan</h3>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    Dukungan penuh bahkan setelah proyek selesai untuk memastikan kesuksesan jangka panjang
                </p>
            </div>
        </div>
    </div>
</section>