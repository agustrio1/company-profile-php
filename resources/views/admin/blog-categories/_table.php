<div class="bg-neutral-primary-soft border border-default rounded-base overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-body">
            <thead class="text-xs uppercase bg-neutral-secondary-medium text-heading">
                <tr>
                    <th scope="col" class="px-6 py-3">Kategori</th>
                    <th scope="col" class="px-6 py-3">Slug</th>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-body mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <p class="text-body font-medium">Tidak ada kategori ditemukan</p>
                                <?php if (isset($search) && $search): ?>
                                    <p class="text-sm text-body mt-1">Coba ubah kata kunci pencarian</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <?php $category = is_array($category) ? (object)$category : $category; ?>
                        <tr class="border-t border-default hover:bg-neutral-secondary-medium transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-brand-soft rounded flex items-center justify-center mr-3 text-fg-brand font-semibold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-heading"><?= htmlspecialchars($category->name ?? '') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs bg-neutral-tertiary px-2 py-1 rounded text-body"><?= htmlspecialchars($category->slug ?? '') ?></code>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-body"><?= htmlspecialchars($category->description ?? '-') ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a 
                                        href="<?= url('admin/blog-categories/' . ($category->id ?? '') . '/edit') ?>"
                                        class="p-2 text-fg-brand hover:text-fg-brand-strong hover:bg-brand-soft rounded transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button 
                                        hx-get="<?= url('admin/blog-categories/' . ($category->id ?? '') . '/confirm-delete') ?>"
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