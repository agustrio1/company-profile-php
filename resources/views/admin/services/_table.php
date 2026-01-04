<div class="bg-neutral-primary-soft border border-default rounded-base overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-body">
            <thead class="text-xs uppercase bg-neutral-secondary-medium text-heading">
                <tr>
                    <th scope="col" class="px-6 py-3 w-24">Icon</th>
                    <th scope="col" class="px-6 py-3 w-24">Gambar</th>
                    <th scope="col" class="px-6 py-3">Judul</th>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3">Featured</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($services)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-body mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-body font-medium">Tidak ada service ditemukan</p>
                                <?php if (isset($search) && $search): ?>
                                    <p class="text-sm text-body mt-1">Coba ubah kata kunci pencarian</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($services as $service): ?>
                        <?php $service = is_array($service) ? (object)$service : $service; ?>
                        <tr class="border-t border-default hover:bg-neutral-secondary-medium transition-colors">
                            <!-- Icon Column -->
                            <td class="px-6 py-4">
                                <?php if ($service->icon ?? null): ?>
                                    <?php
                                    $iconPath = $service->icon;
                                    if (strpos($iconPath, 'uploads/') !== 0) {
                                        $iconPath = 'uploads/' . ltrim($iconPath, '/');
                                    }
                                    ?>
                                    <img src="<?= asset($iconPath) ?>" 
                                         alt="Icon" 
                                         class="w-12 h-12 object-contain"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2748%27 height=%2748%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%239ca3af%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M13 10V3L4 14h7v7l9-11h-7z%27/%3E%3C/svg%3E';">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-neutral-tertiary rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-body" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Gambar Column -->
                            <td class="px-6 py-4">
                                <?php if ($service->image ?? null): ?>
                                    <?php
                                    $imagePath = $service->image;
                                    if (strpos($imagePath, 'uploads/') !== 0) {
                                        $imagePath = 'uploads/' . ltrim($imagePath, '/');
                                    }
                                    ?>
                                    <img src="<?= asset($imagePath) ?>" 
                                         alt="<?= htmlspecialchars($service->title ?? '') ?>" 
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
                                <div class="font-medium text-heading"><?= htmlspecialchars($service->title ?? '') ?></div>
                                <div class="text-xs text-body mt-1">
                                    <code class="bg-neutral-tertiary px-2 py-1 rounded"><?= htmlspecialchars($service->slug ?? '') ?></code>
                                </div>
                            </td>
                            
                            <!-- Deskripsi Column -->
                            <td class="px-6 py-4">
                                <div class="text-body max-w-xs truncate">
                                    <?= htmlspecialchars(substr(strip_tags($service->description ?? ''), 0, 100)) ?>
                                    <?= strlen(strip_tags($service->description ?? '')) > 100 ? '...' : '' ?>
                                </div>
                            </td>
                            
                            <!-- Featured Column -->
                            <td class="px-6 py-4">
                                <?php if ($service->is_featured ?? false): ?>
                                    <span class="text-xs px-2 py-1 rounded bg-brand-soft text-fg-brand">Featured</span>
                                <?php else: ?>
                                    <span class="text-xs px-2 py-1 rounded bg-neutral-tertiary text-body">Normal</span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Aksi Column -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Edit -->
                                    <a 
                                        href="<?= url('admin/services/' . ($service->id ?? '') . '/edit') ?>"
                                        class="p-2 text-fg-brand hover:text-fg-brand-strong hover:bg-brand-soft rounded transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Delete -->
                                    <button 
                                        hx-get="<?= url('admin/services/' . ($service->id ?? '') . '/confirm-delete') ?>"
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