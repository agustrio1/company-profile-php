<div class="bg-neutral-primary-soft border border-default rounded-base overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-body">
            <thead class="text-xs uppercase bg-neutral-secondary-medium text-heading">
                <tr>
                    <th scope="col" class="px-6 py-3">User</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Roles</th>
                    <th scope="col" class="px-6 py-3">Dibuat</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-body mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="text-body font-medium">Tidak ada user ditemukan</p>
                                <?php if (isset($search) && $search): ?>
                                    <p class="text-sm text-body mt-1">Coba ubah kata kunci pencarian</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <?php
                        // Convert array to object if needed
                        $user = is_array($user) ? (object)$user : $user;
                        ?>
                        <tr class="border-t border-default hover:bg-neutral-secondary-medium transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-brand-soft rounded-full flex items-center justify-center mr-3 text-fg-brand font-semibold">
                                        <?= strtoupper(substr($user->name ?? '', 0, 1)) ?>
                                    </div>
                                    <span class="font-medium text-heading"><?= htmlspecialchars($user->name ?? '') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-body"><?= htmlspecialchars($user->email ?? '') ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <?php 
                                    $roles = $user->roles ?? [];
                                    $roles = is_array($roles) ? $roles : [];
                                    ?>
                                    <?php if (empty($roles)): ?>
                                        <span class="text-xs px-2 py-1 bg-neutral-tertiary text-body rounded">No roles</span>
                                    <?php else: ?>
                                        <?php foreach ($roles as $role): ?>
                                            <?php $role = is_array($role) ? (object)$role : $role; ?>
                                            <span class="text-xs px-2 py-1 bg-brand-soft text-fg-brand rounded">
                                                <?= htmlspecialchars($role->name ?? '') ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-body">
                                    <?= $user->created_at ? date('d M Y', strtotime($user->created_at)) : '-' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a 
                                        href="<?= url('admin/users/' . ($user->id ?? '') . '/edit') ?>"
                                        class="p-2 text-fg-brand hover:text-fg-brand-strong hover:bg-brand-soft rounded transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button 
                                        hx-get="<?= url('admin/users/' . ($user->id ?? '') . '/confirm-delete') ?>"
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