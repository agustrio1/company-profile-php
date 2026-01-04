<?php
$employee = is_array($employee) ? (object)$employee : $employee;
?>

<div 
    x-data="{ show: true, deleting: false }"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="background-color: rgba(0, 0, 0, 0.5);"
    @click.self="if(!deleting) { show = false; setTimeout(() => document.getElementById('delete-modal').innerHTML = '', 300) }"
>
    <div 
        class="bg-neutral-primary-soft border border-default rounded-base shadow-lg p-6 max-w-md w-full"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        @click.stop
    >
        <div class="flex items-start mb-4">
            <div class="w-12 h-12 bg-danger-soft rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                <svg class="w-6 h-6 text-fg-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-heading mb-1">Hapus Anggota</h3>
                <p class="text-sm text-body">
                    Apakah Anda yakin ingin menghapus anggota <strong><?= htmlspecialchars($employee->name ?? '') ?></strong>?
                </p>
                <p class="text-sm text-body mt-2">
                    Foto dan semua data terkait anggota ini akan ikut terhapus.
                </p>
                <p class="text-sm text-danger font-medium mt-2">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        
        <div class="flex justify-end gap-3">
            <button 
                type="button"
                @click="show = false; setTimeout(() => document.getElementById('delete-modal').innerHTML = '', 300)"
                :disabled="deleting"
                class="px-4 py-2 text-sm font-medium text-body bg-neutral-secondary-medium hover:bg-neutral-tertiary rounded-base transition-colors disabled:opacity-50"
            >
                Batal
            </button>
            <button 
                type="button"
                @click="deleting = true"
                :disabled="deleting"
                hx-post="<?= url('admin/employees/' . ($employee->id ?? '')) ?>"
                hx-vals='{"_method": "DELETE", "_csrf_token": "<?= csrf_token() ?>"}'
                hx-swap="none"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-danger hover:bg-danger-strong rounded-base transition-colors disabled:opacity-50"
            >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span x-text="deleting ? 'Menghapus...' : 'Hapus Anggota'"></span>
            </button>
        </div>
    </div>
</div>