<!-- File: resources/views/partials/home/blog.php -->
<section class="py-12 md:py-16 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8 md:mb-12">
            <div>
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Insight & Artikel</h2>
                <p class="text-sm md:text-base lg:text-lg text-gray-600">Baca tips dan strategi bisnis terkini</p>
            </div>
            <a href="/blog" 
               hx-get="/blog" 
               hx-target="#main-content" 
               hx-swap="innerHTML transition:true"
               hx-push-url="true"
               class="hidden md:inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm lg:text-base transition-colors">
                Lihat Semua
                <svg class="w-4 h-4 lg:w-5 lg:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <?php if (!empty($blogs)): ?>
                <?php foreach (array_slice($blogs, 0, 3) as $blog): ?>
                    <?php
                    if (is_array($blog)) {
                        $blog = (object) $blog;
                    }
                    
                    $thumbnailUrl = null;
                    if (!empty($blog->thumbnail)) {
                        $thumbnailUrl = method_exists($blog, 'getThumbnailUrl') 
                            ? $blog->getThumbnailUrl() 
                            : asset('uploads/' . $blog->thumbnail);
                    }
                    ?>
                    <article class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                        <a href="/blog/<?= htmlspecialchars($blog->slug ?? '#') ?>" 
                           hx-get="/blog/<?= htmlspecialchars($blog->slug ?? '#') ?>" 
                           hx-target="#main-content" 
                           hx-swap="innerHTML transition:true"
                           hx-push-url="true"
                           class="block">
                            <div class="h-48 relative overflow-hidden bg-gray-100">
                                <?php if ($thumbnailUrl): ?>
                                    <img src="<?= htmlspecialchars($thumbnailUrl) ?>" 
                                         alt="<?= htmlspecialchars($blog->title ?? 'Blog Post') ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full bg-blue-600 flex items-center justify-center" style="display:none;">
                                        <svg class="w-12 h-12 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                <?php else: ?>
                                    <div class="w-full h-full bg-blue-600 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-5 md:p-6">
                                <div class="flex items-center gap-3 text-xs md:text-sm text-gray-500 mb-3">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <?php 
                                        $createdAt = $blog->created_at ?? date('Y-m-d H:i:s');
                                        echo date('d M Y', strtotime($createdAt)); 
                                        ?>
                                    </span>
                                    <?php if (isset($blog->category) && !empty($blog->category)): ?>
                                        <?php 
                                        $category = is_object($blog->category) ? $blog->category : (object)$blog->category;
                                        ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= htmlspecialchars($category->name ?? 'Uncategorized') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="text-base md:text-lg lg:text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    <?= htmlspecialchars($blog->title ?? 'Untitled Post') ?>
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                    <?php 
                                    $content = $blog->content ?? 'No content available';
                                    echo htmlspecialchars(strip_tags(substr($content, 0, 150))); 
                                    ?>...
                                </p>
                                <span class="text-blue-600 font-semibold text-sm inline-flex items-center group/link">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <article class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="h-48 bg-blue-600 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="p-5 md:p-6">
                            <div class="flex items-center gap-3 text-xs md:text-sm text-gray-400 mb-3">
                                <span>Coming Soon</span>
                            </div>
                            <h3 class="text-base md:text-lg lg:text-xl font-bold text-gray-900 mb-2">Blog Post Title</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                Stay tuned for our latest articles and insights...
                            </p>
                        </div>
                    </article>
                <?php endfor; ?>
            <?php endif; ?>
        </div>

        <div class="text-center mt-8 md:mt-12 md:hidden">
            <a href="/blog" 
               hx-get="/blog" 
               hx-target="#main-content" 
               hx-swap="innerHTML transition:true"
               hx-push-url="true"
               class="inline-flex items-center px-6 py-3 text-blue-600 border-2 border-blue-600 rounded-lg hover:bg-blue-600 hover:text-white font-semibold transition-all">
                Lihat Semua Artikel
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>