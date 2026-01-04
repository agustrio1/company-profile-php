<div class="bg-neutral-primary-soft border border-default rounded-base overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-body">
            <thead class="text-xs uppercase bg-neutral-secondary-medium text-heading">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Team</th>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3">Jumlah Anggota</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($teams)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-body mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-body font-medium">Tidak ada team ditemukan</p>
                                <?php if (isset($search) && $search): ?>
                                    <p class="text-sm text-body mt-1">Coba ubah kata kunci pencarian</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($teams as $team): ?>
                        <?php $team = is_array($team) ? (object)$team : $team; ?>
                        <tr class="border-t border-default hover:bg-neutral-secondary-medium transition-colors">
                            <!-- Nama Team -->
                            <td class="px-6 py-4">
                                <div class="font-medium text-heading"><?= htmlspecialchars($team->name ?? '') ?></div>
                            </td>
                            
                            <!-- Deskripsi -->
                            <td class="px-6 py-4">
                                <div class="text-body max-w-md truncate">
                                    <?= htmlspecialchars(substr($team->description ?? '-', 0, 100)) ?>
                                    <?= strlen($team->description ?? '') > 100 ? '...' : '' ?>
                                </div>
                            </td>
                            
                            <!-- Jumlah Anggota -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-brand-soft text-fg-brand">
                                    <?= count($team->employees ?? []) ?> Anggota
                                </span>
                            </td>
                            
                            <!-- Aksi -->
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Add Employee -->
                                    <a 
                                        href="<?= url('admin/teams/' . ($team->id ?? '') . '/employees/create') ?>"
                                        class="p-2 text-fg-success hover:text-success-strong hover:bg-success-soft rounded transition-colors"
                                        title="Tambah Anggota"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Edit -->
                                    <a 
                                        href="<?= url('admin/teams/' . ($team->id ?? '') . '/edit') ?>"
                                        class="p-2 text-fg-brand hover:text-fg-brand-strong hover:bg-brand-soft rounded transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Delete -->
                                    <button 
                                        hx-get="<?= url('admin/teams/' . ($team->id ?? '') . '/confirm-delete') ?>"
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
                        
                        <!-- Employee List (Expandable) -->
                        <?php if (!empty($team->employees)): ?>
                            <?php foreach ($team->employees as $employee): ?>
                                <?php $employee = is_array($employee) ? (object)$employee : $employee; ?>
                                <tr class="border-t border-default bg-neutral-secondary-soft">
                                    <td class="px-6 py-3 pl-12">
                                        <div class="flex items-center gap-3">
                                            <?php if ($employee->photo ?? null): ?>
                                                <?php
                                                $photoPath = $employee->photo;
                                                if (strpos($photoPath, 'uploads/') !== 0) {
                                                    $photoPath = 'uploads/' . ltrim($photoPath, '/');
                                                }
                                                ?>
                                                <img src="<?= asset($photoPath) ?>" alt="<?= htmlspecialchars($employee->name ?? '') ?>" class="w-8 h-8 rounded-full object-cover">
                                            <?php else: ?>
                                                <div class="w-8 h-8 bg-neutral-tertiary rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-body" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                            <span class="text-sm text-body"><?= htmlspecialchars($employee->name ?? '') ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="text-sm text-body"><?= htmlspecialchars($employee->position ?? '-') ?></span>
                                    </td>
                                    <td class="px-6 py-3"></td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a 
                                                href="<?= url('admin/employees/' . ($employee->id ?? '') . '/edit') ?>"
                                                class="p-1.5 text-fg-brand hover:text-fg-brand-strong hover:bg-brand-soft rounded transition-colors text-xs"
                                                title="Edit Employee"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <button 
                                                hx-get="<?= url('admin/employees/' . ($employee->id ?? '') . '/confirm-delete') ?>"
                                                hx-target="#delete-modal"
                                                hx-swap="innerHTML"
                                                class="p-1.5 text-fg-danger hover:text-danger-strong hover:bg-danger-soft rounded transition-colors text-xs"
                                                title="Hapus Employee"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>