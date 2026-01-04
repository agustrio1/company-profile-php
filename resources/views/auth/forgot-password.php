<?php
$title = 'Lupa Kata Sandi';
ob_start();
?>

<div class="min-h-screen flex items-center justify-center bg-neutral-secondary-soft py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="flex justify-center">
                <img class="h-12 w-auto" src="/images/logo.svg" alt="Logo">
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-heading">
                Lupa Kata Sandi?
            </h2>
            <p class="mt-2 text-center text-sm text-body">
                Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset kata sandi.
            </p>
        </div>

        <?php if (session('error')): ?>
            <div class="p-4 mb-4 text-sm text-danger-strong rounded-base bg-danger-soft border border-danger-subtle" role="alert">
                <span class="font-medium">Error!</span> <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session('success')): ?>
            <div class="p-4 mb-4 text-sm text-success-strong rounded-base bg-success-soft border border-success-subtle" role="alert">
                <div class="flex items-center">
                    <i class="ri-checkbox-circle-line text-xl mr-2"></i>
                    <div>
                        <span class="font-medium">Berhasil!</span> <?= session('success') ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="/forgot-password" method="POST" x-data="forgotPasswordForm()">
            <?= csrf_field() ?>
            
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-heading">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="ri-mail-line text-body"></i>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        x-model="email"
                        class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full pl-10 p-2.5 <?= isset($errors['email']) ? 'border-danger-subtle' : '' ?>" 
                        placeholder="nama@perusahaan.com" 
                        value="<?= old('email') ?>"
                        required
                    >
                </div>
                <?php if (isset($errors['email'])): ?>
                    <p class="mt-2 text-sm text-danger-strong"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="space-y-4">
                <button 
                    type="submit" 
                    :disabled="loading"
                    @click="loading = true"
                    class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-base text-white bg-brand hover:bg-brand-strong focus:outline-none focus:ring-4 focus:ring-brand-medium disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading" class="flex items-center">
                        <i class="ri-mail-send-line mr-2"></i>
                        Kirim Link Reset Kata Sandi
                    </span>
                    <span x-show="loading" class="flex items-center">
                        <i class="ri-loader-4-line animate-spin mr-2"></i>
                        Mengirim...
                    </span>
                </button>

                <div class="text-center">
                    <a href="/login" class="text-sm font-medium text-fg-brand hover:text-fg-brand-strong flex items-center justify-center">
                        <i class="ri-arrow-left-line mr-1"></i>
                        Kembali ke halaman masuk
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function forgotPasswordForm() {
        return {
            email: '<?= old('email') ?>',
            loading: false
        }
    }
</script>

<?php
$content = ob_get_clean();
require view_path('layouts/guest.php');
?>