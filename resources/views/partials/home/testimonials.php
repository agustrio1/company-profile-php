<!-- File: resources/views/partials/home/testimonials.php -->
<section class="py-12 md:py-16 lg:py-20 bg-blue-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-12 md:mb-16">
            <span class="text-blue-600 font-semibold mb-2 block text-sm md:text-base">Testimoni Klien</span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Apa Kata Mereka?</h2>
            <p class="text-gray-600 text-base md:text-lg max-w-2xl mx-auto">
                Kepuasan klien adalah prioritas kami. Simak pengalaman mereka bekerja sama dengan kami
            </p>
        </div>

        <!-- Flowbite Carousel -->
        <div id="testimonial-carousel" class="relative w-full z-0" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-auto overflow-hidden rounded-xl" style="min-height: 350px;">
                <!-- Testimonial 1 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <div class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 px-4">
                        <div class="max-w-3xl mx-auto bg-white rounded-xl p-6 md:p-10 border border-gray-200 shadow-lg">
                            <div class="flex items-center justify-center mb-4 md:mb-5">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-700 mb-6 italic text-base md:text-lg leading-relaxed text-center">
                                "Konsultasi yang sangat membantu! Tim profesional dan responsive. Hasil kerja mereka melampaui ekspektasi kami."
                            </p>
                            <div class="flex items-center justify-center">
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    JD
                                </div>
                                <div class="ml-4">
                                    <p class="font-bold text-gray-900 text-sm md:text-base">John Doe</p>
                                    <p class="text-xs md:text-sm text-gray-600">CEO, Tech Startup</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <div class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 px-4">
                        <div class="max-w-3xl mx-auto bg-white rounded-xl p-6 md:p-10 border border-gray-200 shadow-lg">
                            <div class="flex items-center justify-center mb-4 md:mb-5">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-700 mb-6 italic text-base md:text-lg leading-relaxed text-center">
                                "Strategi bisnis yang diberikan sangat applicable dan langsung berdampak positif pada revenue kami. Highly recommended!"
                            </p>
                            <div class="flex items-center justify-center">
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    SA
                                </div>
                                <div class="ml-4">
                                    <p class="font-bold text-gray-900 text-sm md:text-base">Sarah Anderson</p>
                                    <p class="text-xs md:text-sm text-gray-600">Marketing Director</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <div class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 px-4">
                        <div class="max-w-3xl mx-auto bg-white rounded-xl p-6 md:p-10 border border-gray-200 shadow-lg">
                            <div class="flex items-center justify-center mb-4 md:mb-5">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-700 mb-6 italic text-base md:text-lg leading-relaxed text-center">
                                "Partner terbaik untuk transformasi digital perusahaan kami. Proses smooth dan hasilnya luar biasa memuaskan."
                            </p>
                            <div class="flex items-center justify-center">
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    MP
                                </div>
                                <div class="ml-4">
                                    <p class="font-bold text-gray-900 text-sm md:text-base">Michael Peterson</p>
                                    <p class="text-xs md:text-sm text-gray-600">Operations Manager</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider indicators -->
            <div class="absolute z-30 flex -translate-x-1/2 -bottom-8 left-1/2 space-x-3">
                <button type="button" class="w-3 h-3 rounded-full bg-blue-600" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
                <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
                <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
            </div>

            <!-- Slider controls -->
            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-2 md:px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                <span class="inline-flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/80 group-hover:bg-white group-focus:ring-4 group-focus:ring-blue-300 group-focus:outline-none shadow-lg">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7"/>
                    </svg>
                    <span class="sr-only">Previous</span>
                </span>
            </button>
            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-2 md:px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                <span class="inline-flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/80 group-hover:bg-white group-focus:ring-4 group-focus:ring-blue-300 group-focus:outline-none shadow-lg">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                    </svg>
                    <span class="sr-only">Next</span>
                </span>
            </button>
        </div>
    </div>
</section>