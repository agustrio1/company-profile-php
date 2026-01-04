<div class="bg-neutral-primary-soft border border-default rounded-base overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-body">
            <thead class="text-xs uppercase bg-neutral-secondary-medium text-heading">
                <tr>
                    <th scope="col" class="px-6 py-3 w-24">Gambar</th>
                    <th scope="col" class="px-6 py-3">Judul</th>
                    <th scope="col" class="px-6 py-3">Kategori</th>
                    <th scope="col" class="px-6 py-3">Author</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Dibuat</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($blogs)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-body mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-body font-medium">Tidak ada blog ditemukan</p>
                                <?php if (isset($search) && $search): ?>
                                    <p class="text-sm text-body mt-1">Coba ubah kata kunci pencarian</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($blogs as $blog): ?>
                        <?php $blog = is_array($blog) ? (object)$blog : $blog; ?>
                        <tr class="border-t border-default hover:bg-neutral-secondary-medium transition-colors">
                            <!-- Gambar Column -->
                            <td class="px-6 py-4">
                                <?php if ($blog->thumbnail ?? null): ?>
                                    <?php
                                    // Normalize path - ensure it has 'uploads/' prefix
                                    $thumbnailPath = $blog->thumbnail;
                                    if (strpos($thumbnailPath, 'uploads/') !== 0) {
                                        $thumbnailPath = 'uploads/' . ltrim($thumbnailPath, '/');
                                    }
                                    ?>
                                    <img src="<?= asset($thumbnailPath) ?>" 
                                         alt="<?= htmlspecialchars($blog->title ?? '') ?>" 
                                         class="w-16 h-16 object-cover rounded"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2764%27 height=%2764%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%239ca3af%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z%27/%3E%3C/svg%3E';">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-neutral-tertiary rounded flex items-center justify-center">
                                        <svg class="w-8 h-8 text-body" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Judul Column -->
                            <td class="px-6 py-4">
                                <div class="font-medium text-heading"><?= htmlspecialchars($blog->title ?? '') ?></div>
                                <div class="text-xs text-body mt-1">
                                    <code class="bg-neutral-tertiary px-2 py-1 rounded"><?= htmlspecialchars($blog->slug ?? '') ?></code>
                                </div>
                            </td>
                            
                            <!-- Kategori Column -->
                            <td class="px-6 py-4">
                                <span class="text-body"><?= htmlspecialchars($blog->category_name ?? '-') ?></span>
                            </td>
                            
                            <!-- Author Column -->
                            <td class="px-6 py-4">
                                <span class="text-body"><?= htmlspecialchars($blog->author_name ?? '-') ?></span>
                            </td>
                            
                            <!-- Status Column -->
                            <td class="px-6 py-4">
                                <?php
                                $status = $blog->status ?? 'draft';
                                $statusColors = [
                                    'published' => 'bg-success-soft text-fg-success',
                                    'draft' => 'bg-warning-soft text-fg-warning',
                                    'archived' => 'bg-neutral-tertiary text-body'
                                ];
                                $statusLabels = [
                                    'published' => 'Published',
                                    'draft' => 'Draft',
                                    'archived' => 'Archived'
                                ];
                                ?>
                                <span class="text-xs px-2 py-1 rounded <?= $statusColors[$status] ?? 'bg-neutral-tertiary text-body' ?>">
                                    <?= $statusLabels[$status] ?? ucfirst($status) ?>
                                </span>
                            </td>
                            
                            <!-- Dibuat Column -->
                            <td class="px-6 py-4">
                                <span class="text-body">
                                    <?= $blog->created_at ? date('d M Y', strtotime($blog->created_at)) : '-' ?>
                                </span>
                            </td>
                            
                            <!-- Aksi Column -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- Edit -->
                                    <a 
                                        href="<?= url('admin/blogs/' . ($blog->id ?? '') . '/edit') ?>"
                                        class="p-2 text-fg-brand hover:text-fg-brand-strong hover:bg-brand-soft rounded transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Delete -->
                                    <button 
                                        hx-get="<?= url('admin/blogs/' . ($blog->id ?? '') . '/confirm-delete') ?>"
                                        hx-target="#delete-modal"
                                        hx-swap="innerHTML"
                                        class="p-2 text-fg-danger hover:text-danger-strong hover:bg-danger-soft rounded transition-colors"
                                        title="Hapus"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>