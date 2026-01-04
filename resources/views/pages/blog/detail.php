<?php
$isHtmx = isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';

if (!$isHtmx) {
    $title = $title ?? ($blog->title ?? 'Detail Artikel');
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
               hx-push-url="true"
               class="hover:text-brand transition-colors">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="/blog" 
               hx-get="/blog"
               hx-target="#main-content"
               hx-push-url="true"
               class="hover:text-brand transition-colors">Blog</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-heading font-medium line-clamp-1"><?= htmlspecialchars($blog->title) ?></span>
        </div>
    </div>
</section>

<!-- Article Content -->
<article class="py-16 bg-neutral-primary">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <header class="mb-8 animate-fade-in">
            <!-- Category -->
            <?php if (isset($blog->category) && $blog->category): ?>
                <a href="/blog/category/<?= htmlspecialchars($blog->category->slug) ?>"
                   hx-get="/blog/category/<?= htmlspecialchars($blog->category->slug) ?>"
                   hx-target="#main-content"
                   hx-push-url="true"
                   class="inline-block px-4 py-2 bg-brand text-white text-sm font-semibold rounded-lg hover:bg-brand-strong transition-colors mb-4">
                    <?= htmlspecialchars($blog->category->name) ?>
                </a>
            <?php endif; ?>

            <!-- Title -->
            <h1 class="text-3xl lg:text-4xl font-bold text-heading mb-6"><?= htmlspecialchars($blog->title) ?></h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-6 text-sm text-body pb-6 border-b border-neutral-tertiary">
                <?php if (isset($blog->author)): ?>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-brand/10 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-heading"><?= htmlspecialchars($blog->author->name ?? 'Admin') ?></p>
                            <p class="text-xs">Author</p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($blog->created_at): ?>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span><?= date('d F Y', strtotime($blog->created_at)) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Featured Image -->
        <?php if ($blog->thumbnail): ?>
            <div class="mb-12 animate-fade-in">
                <img src="/uploads/blogs/<?= htmlspecialchars(basename($blog->thumbnail)) ?>" 
                     alt="<?= htmlspecialchars($blog->title) ?>" 
                     class="w-full h-auto rounded-2xl">
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="prose prose-lg max-w-none mb-12 animate-fade-in">
            <div class="ql-editor text-body leading-relaxed">
                <?= $blog->content ?>
            </div>
        </div>

        <!-- Additional Images -->
        <?php if (!empty($blog->images)): ?>
            <div class="mb-12 animate-fade-in">
                <h3 class="text-2xl font-bold text-heading mb-6">Galeri</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($blog->images as $image): ?>
                        <div class="relative overflow-hidden rounded-lg aspect-video bg-neutral-secondary">
                            <img src="/uploads/blogs/<?= htmlspecialchars(basename($image->image)) ?>" 
                                 alt="<?= htmlspecialchars($image->caption ?? $blog->title) ?>" 
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Share Buttons -->
        <div class="py-6 border-t border-neutral-tertiary mb-12 animate-fade-in">
            <p class="text-sm font-semibold text-heading mb-4">Bagikan Artikel:</p>
            <div class="flex flex-wrap gap-3">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($currentUrl) ?>" 
                   target="_blank"
                   class="px-4 py-2 bg-[#1877F2] text-white rounded-lg hover:opacity-90 transition-opacity flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode($currentUrl) ?>&text=<?= urlencode($blog->title) ?>" 
                   target="_blank"
                   class="px-4 py-2 bg-[#1DA1F2] text-white rounded-lg hover:opacity-90 transition-opacity flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    Twitter
                </a>
                <a href="https://wa.me/?text=<?= urlencode($blog->title . ' - ' . $currentUrl) ?>" 
                   target="_blank"
                   class="px-4 py-2 bg-[#25D366] text-white rounded-lg hover:opacity-90 transition-opacity flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    WhatsApp
                </a>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between items-center mb-12 animate-fade-in">
            <a href="/blog" 
               hx-get="/blog"
               hx-target="#main-content"
               hx-push-url="true"
               class="inline-flex items-center px-6 py-3 bg-white border-2 border-brand text-brand rounded-lg hover:bg-brand hover:text-white transition-all duration-300 font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Blog
            </a>
        </div>
    </div>
</article>

<!-- Related Posts -->
<?php if (!empty($relatedBlogs)): ?>
<section class="py-16 bg-white border-t border-neutral-tertiary">
    <div class="max-w-screen-xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-heading mb-8 text-center">Artikel Terkait</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($relatedBlogs as $related): ?>
                <article class="bg-white rounded-2xl overflow-hidden border border-neutral-tertiary hover:border-brand transition-all duration-300 hover:-translate-y-2 group">
                    <a href="/blog/<?= htmlspecialchars($related->slug) ?>"
                       hx-get="/blog/<?= htmlspecialchars($related->slug) ?>"
                       hx-target="#main-content"
                       hx-push-url="true"
                       class="block relative h-40 overflow-hidden bg-neutral-secondary">
                        <?php if ($related->thumbnail): ?>
                            <img src="/uploads/blogs/<?= htmlspecialchars(basename($related->thumbnail)) ?>" 
                                 alt="<?= htmlspecialchars($related->title) ?>" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-body/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-heading mb-2 line-clamp-2 group-hover:text-brand transition-colors">
                            <a href="/blog/<?= htmlspecialchars($related->slug) ?>"
                               hx-get="/blog/<?= htmlspecialchars($related->slug) ?>"
                               hx-target="#main-content"
                               hx-push-url="true">
                                <?= htmlspecialchars($related->title) ?>
                            </a>
                        </h3>
                        <p class="text-body text-sm line-clamp-2">
                            <?= htmlspecialchars(strip_tags(substr($related->content, 0, 100))) ?>...
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

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

/* Quill Editor Styles */
.ql-editor {
    color: var(--color-body);
    font-size: 1.125rem;
    line-height: 1.75;
    padding: 0;
}

.ql-editor h1 {
    color: var(--color-heading);
    font-weight: 700;
    font-size: 2.25rem;
    margin-top: 2.5rem;
    margin-bottom: 1.25rem;
    line-height: 1.2;
}

.ql-editor h2 {
    color: var(--color-heading);
    font-weight: 700;
    font-size: 1.875rem;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.ql-editor h3 {
    color: var(--color-heading);
    font-weight: 700;
    font-size: 1.5rem;
    margin-top: 1.75rem;
    margin-bottom: 0.875rem;
    line-height: 1.4;
}

.ql-editor h4 {
    color: var(--color-heading);
    font-weight: 600;
    font-size: 1.25rem;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.ql-editor h5 {
    color: var(--color-heading);
    font-weight: 600;
    font-size: 1.125rem;
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
}

.ql-editor h6 {
    color: var(--color-heading);
    font-weight: 600;
    font-size: 1rem;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

.ql-editor p {
    margin-bottom: 1.25rem;
    line-height: 1.75;
}

.ql-editor strong {
    font-weight: 700;
    color: var(--color-heading);
}

.ql-editor em {
    font-style: italic;
}

.ql-editor u {
    text-decoration: underline;
}

.ql-editor s {
    text-decoration: line-through;
}

/* Lists */
.ql-editor ul, .ql-editor ol {
    margin-bottom: 1.25rem;
    padding-left: 1.75rem;
}

.ql-editor ul {
    list-style-type: disc;
}

.ql-editor ol {
    list-style-type: decimal;
}

.ql-editor li {
    margin-bottom: 0.5rem;
    line-height: 1.75;
}

.ql-editor li > ul,
.ql-editor li > ol {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

/* Nested lists */
.ql-editor ul ul,
.ql-editor ol ul {
    list-style-type: circle;
}

.ql-editor ul ul ul,
.ql-editor ol ul ul,
.ql-editor ol ol ul,
.ql-editor ul ol ul {
    list-style-type: square;
}

/* Blockquote */
.ql-editor blockquote {
    border-left: 4px solid var(--color-brand);
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: var(--color-body);
    background-color: var(--color-neutral-secondary);
    padding: 1.25rem 1.5rem;
    border-radius: 0.5rem;
}

/* Code */
.ql-editor code {
    background-color: var(--color-neutral-secondary);
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.875rem;
    color: var(--color-brand);
}

.ql-editor pre {
    background-color: var(--color-neutral-secondary);
    padding: 1.25rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.ql-editor pre code {
    background-color: transparent;
    padding: 0;
    color: var(--color-heading);
    font-size: 0.875rem;
    line-height: 1.5;
}

/* Links */
.ql-editor a {
    color: var(--color-brand);
    text-decoration: underline;
    transition: color 0.2s;
}

.ql-editor a:hover {
    color: var(--color-brand-strong);
}

/* Images */
.ql-editor img {
    max-width: 100%;
    height: auto;
    border-radius: 1rem;
    margin: 1.5rem 0;
    display: block;
}

/* Video */
.ql-editor iframe,
.ql-editor video {
    max-width: 100%;
    border-radius: 1rem;
    margin: 1.5rem 0;
}

/* Table */
.ql-editor table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.ql-editor table td,
.ql-editor table th {
    border: 1px solid var(--color-neutral-tertiary);
    padding: 0.75rem;
    text-align: left;
}

.ql-editor table th {
    background-color: var(--color-brand);
    color: white;
    font-weight: 600;
}

.ql-editor table tr:nth-child(even) {
    background-color: var(--color-neutral-secondary);
}

/* Horizontal Rule */
.ql-editor hr {
    border: none;
    border-top: 2px solid var(--color-neutral-tertiary);
    margin: 2rem 0;
}

/* Text Alignment */
.ql-editor .ql-align-center {
    text-align: center;
}

.ql-editor .ql-align-right {
    text-align: right;
}

.ql-editor .ql-align-justify {
    text-align: justify;
}

/* Text Colors */
.ql-editor .ql-color-red {
    color: #e74c3c;
}

.ql-editor .ql-color-orange {
    color: #e67e22;
}

.ql-editor .ql-color-yellow {
    color: #f39c12;
}

.ql-editor .ql-color-green {
    color: #27ae60;
}

.ql-editor .ql-color-blue {
    color: #3498db;
}

.ql-editor .ql-color-purple {
    color: #9b59b6;
}

/* Background Colors */
.ql-editor .ql-bg-red {
    background-color: #e74c3c20;
}

.ql-editor .ql-bg-orange {
    background-color: #e67e2220;
}

.ql-editor .ql-bg-yellow {
    background-color: #f39c1220;
}

.ql-editor .ql-bg-green {
    background-color: #27ae6020;
}

.ql-editor .ql-bg-blue {
    background-color: #3498db20;
}

.ql-editor .ql-bg-purple {
    background-color: #9b59b620;
}

/* Font Sizes */
.ql-editor .ql-size-small {
    font-size: 0.875rem;
}

.ql-editor .ql-size-large {
    font-size: 1.5rem;
}

.ql-editor .ql-size-huge {
    font-size: 2rem;
}

/* Indentation */
.ql-editor .ql-indent-1 {
    padding-left: 3em;
}

.ql-editor .ql-indent-2 {
    padding-left: 6em;
}

.ql-editor .ql-indent-3 {
    padding-left: 9em;
}

.ql-editor .ql-indent-4 {
    padding-left: 12em;
}

.ql-editor .ql-indent-5 {
    padding-left: 15em;
}

.ql-editor .ql-indent-6 {
    padding-left: 18em;
}

.ql-editor .ql-indent-7 {
    padding-left: 21em;
}

.ql-editor .ql-indent-8 {
    padding-left: 24em;
}

/* First Paragraph */
.ql-editor > p:first-child {
    margin-top: 0;
}

/* Last Element */
.ql-editor > *:last-child {
    margin-bottom: 0;
}
</style>

<?php
if (!$isHtmx) {
    $content = ob_get_clean();
    require view_path('layouts/app.php');
}
?>