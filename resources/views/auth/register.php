<?php
$title = 'Daftar';
ob_start();
?>

<div class="min-h-screen flex items-center justify-center bg-neutral-secondary-soft py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="flex justify-center">
                <img class="h-12 w-auto" src="/images/logo.svg" alt="Logo">
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-heading">
                Buat Akun Baru
            </h2>
            <p class="mt-2 text-center text-sm text-body">
                Sudah punya akun?
                <a href="/login" class="font-medium text-fg-brand hover:text-fg-brand-strong">
                    Masuk di sini
                </a>
            </p>
        </div>

        <?php if (session('error')): ?>
            <div class="p-4 mb-4 text-sm text-danger-strong rounded-base bg-danger-soft border border-danger-subtle" role="alert">
                <span class="font-medium">Error!</span> <?= session('error') ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="/register" method="POST" x-data="registerForm()">
            <?= csrf_field() ?>
            
            <div class="space-y-4 rounded-base">
                <!-- Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-heading">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="ri-user-line text-body"></i>
                        </div>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            x-model="name"
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full pl-10 p-2.5 <?= isset($errors['name']) ? 'border-danger-subtle' : '' ?>" 
                            placeholder="John Doe" 
                            value="<?= old('name') ?>"
                            required
                        >
                    </div>
                    <?php if (isset($errors['name'])): ?>
                        <p class="mt-2 text-sm text-danger-strong"><?= $errors['name'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
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

                <!-- Password -->
                <div x-data="{ showPassword: false }">
                    <label for="password" class="block mb-2 text-sm font-medium text-heading">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="ri-lock-password-line text-body"></i>
                        </div>
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            name="password" 
                            id="password" 
                            x-model="password"
                            @input="checkPasswordStrength"
                            placeholder="••••••••" 
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full pl-10 pr-10 p-2.5 <?= isset($errors['password']) ? 'border-danger-subtle' : '' ?>" 
                            required
                        >
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-body hover:text-heading"
                        >
                            <i :class="showPassword ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                        </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div x-show="password.length > 0" class="mt-2">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-neutral-tertiary rounded-full h-2">
                                <div 
                                    class="h-2 rounded-full transition-all duration-300"
                                    :class="{
                                        'bg-danger w-1/4': passwordStrength === 'lemah',
                                        'bg-warning w-2/4': passwordStrength === 'sedang',
                                        'bg-success w-3/4': passwordStrength === 'kuat',
                                        'bg-success-strong w-full': passwordStrength === 'sangat kuat'
                                    }"
                                ></div>
                            </div>
                            <span class="text-xs text-body capitalize" x-text="passwordStrength"></span>
                        </div>
                    </div>
                    
                    <?php if (isset($errors['password'])): ?>
                        <p class="mt-2 text-sm text-danger-strong"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password -->
                <div x-data="{ showConfirmPassword: false }">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-heading">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="ri-lock-password-line text-body"></i>
                        </div>
                        <input 
                            :type="showConfirmPassword ? 'text' : 'password'" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            x-model="passwordConfirmation"
                            placeholder="••••••••" 
                            class="bg-neutral-primary border border-default text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full pl-10 pr-10 p-2.5 <?= isset($errors['password_confirmation']) ? 'border-danger-subtle' : '' ?>" 
                            required
                        >
                        <button 
                            type="button" 
                            @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-body hover:text-heading"
                        >
                            <i :class="showConfirmPassword ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                        </button>
                    </div>
                    
                    <p x-show="passwordConfirmation && password !== passwordConfirmation" class="mt-2 text-sm text-danger-strong">
                        Kata sandi tidak cocok
                    </p>
                    
                    <?php if (isset($errors['password_confirmation'])): ?>
                        <p class="mt-2 text-sm text-danger-strong"><?= $errors['password_confirmation'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            id="terms" 
                            name="terms" 
                            type="checkbox" 
                            class="w-4 h-4 border border-default rounded bg-neutral-primary focus:ring-3 focus:ring-brand"
                            required
                        >
                    </div>
                    <label for="terms" class="ml-2 text-sm font-medium text-body">
                        Saya setuju dengan <a href="#" class="text-fg-brand hover:underline">Syarat dan Ketentuan</a>
                    </label>
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    :disabled="loading || password !== passwordConfirmation"
                    @click="loading = true"
                    class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-base text-white bg-brand hover:bg-brand-strong focus:outline-none focus:ring-4 focus:ring-brand-medium disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading" class="flex items-center">
                        <i class="ri-user-add-line mr-2"></i>
                        Buat Akun
                    </span>
                    <span x-show="loading" class="flex items-center">
                        <i class="ri-loader-4-line animate-spin mr-2"></i>
                        Membuat akun...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function registerForm() {
        return {
            name: '<?= old('name') ?>',
            email: '<?= old('email') ?>',
            password: '',
            passwordConfirmation: '',
            passwordStrength: '',
            loading: false,
            
            checkPasswordStrength() {
                const password = this.password;
                let strength = 0;
                
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/)) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;
                
                if (strength <= 2) this.passwordStrength = 'lemah';
                else if (strength === 3) this.passwordStrength = 'sedang';
                else if (strength === 4) this.passwordStrength = 'kuat';
                else this.passwordStrength = 'sangat kuat';
            }
        }
    }
</script>

<?php
$content = ob_get_clean();
require view_path('layouts/guest.php');
?>