<?php
$isHtmx = isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';

if (!$isHtmx) {
    $title = $title ?? 'Blog & Artikel';
    ob_start();
}
?>

<?php if (!$isHtmx): ?>
<!-- Hero Section - Only show on initial load -->
<section class="bg-white border-b border-neutral-tertiary">
    <div class="max-w-screen-xl mx-auto px-4 py-16 animate-fade-in">
        <div class="text-center mb-8">
            <h1 class="text-4xl lg:text-5xl font-bold text-heading mb-4">Blog & Artikel</h1>
            <p class="text-xl text-body max-w-2xl mx-auto">Insight, tips, dan strategi bisnis dari para ahli</p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto">
            <div x-data="{ search: '<?= htmlspecialchars($search ?? '') ?>' }">
                <form class="relative" 
                      hx-get="/blog" 
                      hx-target="#blog-content"
                      hx-push-url="true"
                      hx-indicator="#search-indicator">
                    <input type="text" 
                           name="search" 
                           x-model="search"
                           placeholder="Cari artikel..." 
                           class="w-full px-6 py-4 pr-32 text-lg border-2 border-neutral-tertiary rounded-xl focus:border-brand focus:ring-2 focus:ring-brand/20 transition-colors">
                    <button type="submit" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2 bg-brand text-white rounded-lg hover:bg-brand-strong transition-colors font-semibold">
                        <span id="search-indicator" class="htmx-indicator">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span class="htmx-indicator-hidden">Cari</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<div id="blog-content">
    <!-- Categories -->
    <section class="py-8 bg-neutral-primary border-b border-neutral-tertiary">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="flex items-center gap-3 overflow-x-auto pb-2">
                <span class="text-sm font-medium text-body whitespace-nowrap">Kategori:</span>
                <a href="/blog" 
                   hx-get="/blog"
                   hx-target="#blog-content"
                   hx-push-url="true"
                   class="px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap transition-colors <?= empty($search) && !isset($category) ? 'bg-brand text-white' : 'bg-white text-body hover:bg-brand/10' ?>">
                    Semua
                </a>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <a href="/blog/category/<?= htmlspecialchars($cat->slug) ?>" 
                           hx-get="/blog/category/<?= htmlspecialchars($cat->slug) ?>"
                           hx-target="#blog-content"
                           hx-push-url="true"
                           class="px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap transition-colors bg-white text-body hover:bg-brand/10">
                            <?= htmlspecialchars($cat->name) ?>
                            <?php if (isset($cat->blog_count) && $cat->blog_count > 0): ?>
                                <span class="ml-1 text-xs text-body/60">(<?= $cat->blog_count ?>)</span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Blog Grid -->
    <section class="py-16 bg-neutral-primary">
        <div class="max-w-screen-xl mx-auto px-4">
            <?php if (!empty($blogs)): ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <?php foreach ($blogs as $blog): ?>
                        <article class="bg-white rounded-2xl overflow-hidden border border-neutral-tertiary hover:border-brand transition-all duration-300 hover:-translate-y-2 group">
                            <!-- Thumbnail -->
                            <a href="/blog/<?= htmlspecialchars($blog->slug) ?>"
                               hx-get="/blog/<?= htmlspecialchars($blog->slug) ?>"
                               hx-target="#main-content"
                               hx-push-url="true"
                               class="block relative h-48 overflow-hidden bg-neutral-secondary">
                                <?php if ($blog->thumbnail): ?>
                                    <img src="/uploads/blogs/<?= htmlspecialchars(basename($blog->thumbnail)) ?>" 
                                         alt="<?= htmlspecialchars($blog->title) ?>" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-body/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Category Badge -->
                                <?php if (isset($blog->category) && $blog->category): ?>
                                    <span class="absolute top-4 left-4 px-3 py-1 bg-brand text-white text-xs font-semibold rounded-lg">
                                        <?= htmlspecialchars($blog->category->name ?? '') ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <!-- Content -->
                            <div class="p-6">
                                <!-- Meta Info -->
                                <div class="flex items-center gap-4 text-xs text-body mb-3">
                                    <?php if (isset($blog->author)): ?>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span><?= htmlspecialchars($blog->author->name ?? 'Admin') ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($blog->created_at): ?>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span><?= date('d M Y', strtotime($blog->created_at)) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-heading mb-3 line-clamp-2 group-hover:text-brand transition-colors">
                                    <a href="/blog/<?= htmlspecialchars($blog->slug) ?>"
                                       hx-get="/blog/<?= htmlspecialchars($blog->slug) ?>"
                                       hx-target="#main-content"
                                       hx-push-url="true">
                                        <?= htmlspecialchars($blog->title) ?>
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-body text-sm leading-relaxed mb-4 line-clamp-3">
                                    <?= htmlspecialchars(strip_tags(substr($blog->content, 0, 150))) ?>...
                                </p>

                                <!-- Read More -->
                                <a href="/blog/<?= htmlspecialchars($blog->slug) ?>" 
                                   hx-get="/blog/<?= htmlspecialchars($blog->slug) ?>"
                                   hx-target="#main-content"
                                   hx-push-url="true"
                                   class="inline-flex items-center text-brand font-semibold hover:underline group/link">
                                    <span>Baca Selengkapnya</span>
                                    <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['last_page'] > 1): ?>
                    <div class="flex justify-center">
                        <nav class="flex items-center gap-2">
                            <!-- Previous -->
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="/blog?page=<?= $pagination['current_page'] - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                                   hx-get="/blog?page=<?= $pagination['current_page'] - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                                   hx-target="#blog-content"
                                   hx-push-url="true"
                                   class="px-4 py-2 bg-white border border-neutral-tertiary rounded-lg hover:bg-brand hover:text-white hover:border-brand transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php 
                            $start = max(1, $pagination['current_page'] - 2);
                            $end = min($pagination['last_page'], $pagination['current_page'] + 2);
                            
                            for ($i = $start; $i <= $end; $i++): 
                            ?>
                                <a href="/blog?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                                   hx-get="/blog?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                                   hx-target="#blog-content"
                                   hx-push-url="true"
                                   class="px-4 py-2 rounded-lg transition-colors <?= $i === $pagination['current_page'] ? 'bg-brand text-white' : 'bg-white border border-neutral-tertiary hover:bg-brand hover:text-white hover:border-brand' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <!-- Next -->
                            <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                <a href="/blog?page=<?= $pagination['current_page'] + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                                   hx-get="/blog?page=<?= $pagination['current_page'] + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                                   hx-target="#blog-content"
                                   hx-push-url="true"
                                   class="px-4 py-2 bg-white border border-neutral-tertiary rounded-lg hover:bg-brand hover:text-white hover:border-brand transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-body/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-heading mb-2">Tidak Ada Artikel</h3>
                    <p class="text-body mb-6">
                        <?= $search ? 'Tidak ditemukan artikel dengan kata kunci "' . htmlspecialchars($search) . '"' : 'Belum ada artikel yang dipublikasikan' ?>
                    </p>
                    <?php if ($search): ?>
                        <a href="/blog" 
                           hx-get="/blog"
                           hx-target="#blog-content"
                           hx-push-url="true"
                           class="inline-flex items-center px-6 py-3 bg-brand text-white rounded-lg hover:bg-brand-strong transition-colors font-semibold">
                            Lihat Semua Artikel
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

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

.htmx-indicator {
    display: none;
}

.htmx-request .htmx-indicator {
    display: inline-block;
}

.htmx-request .htmx-indicator-hidden {
    display: none;
}
</style>

<?php
if (!$isHtmx) {
    $content = ob_get_clean();
    require view_path('layouts/app.php');
}
?>