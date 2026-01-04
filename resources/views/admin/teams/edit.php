<?php
$title = 'Edit Team';
ob_start();
?>

<div class="max-w-4xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-body mb-2">
            <a href="<?= url('admin/teams') ?>" class="hover:text-fg-brand">Team</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-heading">Edit Team</span>
        </div>
        <h1 class="text-2xl font-bold text-heading">Edit Team</h1>
        <p class="text-sm text-body mt-1">Edit informasi team</p>
    </div>

    <div class="bg-neutral-primary-soft border border-default rounded-base p-6">
        <form action="<?= url('admin/teams/' . $team->id) ?>" method="POST">
            <?= csrf_field() ?>
            <?= method_field('PUT') ?>

            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-heading mb-2">
                        Nama Team <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?= old('name', $team->name ?? '') ?>"
                        required
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Masukkan nama team..."
                    >
                    <?php if (hasError('name')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('name') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-heading mb-2">
                        Deskripsi <span class="text-xs text-body font-normal">(Opsional)</span>
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-2.5"
                        placeholder="Deskripsi singkat tentang team..."
                    ><?= old('description', $team->description ?? '') ?></textarea>
                    <?php if (hasError('description')): ?>
                        <p class="mt-1 text-sm text-danger"><?= error('description') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Employee List -->
                <?php if (!empty($team->employees)): ?>
                    <div class="border-t border-default pt-5">
                        <h3 class="text-lg font-semibold text-heading mb-4">Anggota Team (<?= count($team->employees) ?>)</h3>
                        <div class="space-y-2">
                            <?php foreach ($team->employees as $employee): ?>
                                <?php $employee = is_array($employee) ? (object)$employee : $employee; ?>
                                <div class="flex items-center justify-between p-3 bg-neutral-secondary-soft rounded-base border border-default">
                                    <div class="flex items-center gap-3">
                                        <?php if ($employee->photo ?? null): ?>
                                            <?php
                                            $photoPath = $employee->photo;
                                            if (strpos($photoPath, 'uploads/') !== 0) {
                                                $photoPath = 'uploads/' . ltrim($photoPath, '/');
                                            }
                                            ?>
                                            <img src="<?= asset($photoPath) ?>" alt="<?= htmlspecialchars($employee->name ?? '') ?>" class="w-10 h-10 rounded-full object-cover">
                                        <?php else: ?>
                                            <div class="w-10 h-10 bg-neutral-tertiary rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-body" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="font-medium text-heading"><?= htmlspecialchars($employee->name ?? '') ?></div>
                                            <div class="text-sm text-body"><?= htmlspecialchars($employee->position ?? '-') ?></div>
                                        </div>
                                    </div>
                                    <a 
                                        href="<?= url('admin/employees/' . ($employee->id ?? '') . '/edit') ?>"
                                        class="text-sm text-fg-brand hover:text-fg-brand-strong"
                                    >
                                        Edit
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a 
                            href="<?= url('admin/teams/' . $team->id . '/employees/create') ?>"
                            class="inline-flex items-center mt-3 px-4 py-2 text-sm font-medium text-brand bg-brand-soft hover:bg-brand-medium rounded-base transition-colors"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Anggota
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-default">
                <button 
                    type="submit"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-brand hover:bg-brand-strong focus:outline-none focus:ring-4 focus:ring-brand-medium rounded-base transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Team
                </button>
                <a 
                    href="<?= url('admin/teams') ?>"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary focus:outline-none focus:ring-4 focus:ring-neutral-tertiary rounded-base transition-colors"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require view_path('layouts/admin.php');
?>