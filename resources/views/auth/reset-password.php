<?php
$title = 'Reset Kata Sandi';
ob_start();
?>

<div class="min-h-screen flex items-center justify-center bg-neutral-secondary-soft py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="flex justify-center">
                <img class="h-12 w-auto" src="/images/logo.svg" alt="Logo">
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-heading">
                Reset Kata Sandi Anda
            </h2>
            <p class="mt-2 text-center text-sm text-body">
                Silakan masukkan kata sandi baru Anda di bawah ini.
            </p>
        </div>

        <?php if (session('error')): ?>
            <div class="p-4 mb-4 text-sm text-danger-strong rounded-base bg-danger-soft border border-danger-subtle" role="alert">
                <span class="font-medium">Error!</span> <?= session('error') ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="/reset-password" method="POST" x-data="resetPasswordForm()">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= $token ?? '' ?>">
            
            <div class="space-y-4">
                <!-- New Password -->
                <div x-data="{ showPassword: false }">
                    <label for="password" class="block mb-2 text-sm font-medium text-heading">Kata Sandi Baru</label>
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
                        
                        <!-- Password Requirements -->
                        <div class="mt-3 space-y-2 text-xs">
                            <div class="flex items-center" :class="password.length >= 8 ? 'text-success-strong' : 'text-body'">
                                <i class="ri-checkbox-circle-fill mr-1" x-show="password.length >= 8"></i>
                                <i class="ri-checkbox-blank-circle-line mr-1" x-show="password.length < 8"></i>
                                Minimal 8 karakter
                            </div>
                            <div class="flex items-center" :class="password.match(/[a-z]/) && password.match(/[A-Z]/) ? 'text-success-strong' : 'text-body'">
                                <i class="ri-checkbox-circle-fill mr-1" x-show="password.match(/[a-z]/) && password.match(/[A-Z]/)"></i>
                                <i class="ri-checkbox-blank-circle-line mr-1" x-show="!(password.match(/[a-z]/) && password.match(/[A-Z]/))"></i>
                                Kombinasi huruf besar & kecil
                            </div>
                            <div class="flex items-center" :class="password.match(/[0-9]/) ? 'text-success-strong' : 'text-body'">
                                <i class="ri-checkbox-circle-fill mr-1" x-show="password.match(/[0-9]/)"></i>
                                <i class="ri-checkbox-blank-circle-line mr-1" x-show="!password.match(/[0-9]/)"></i>
                                Minimal satu angka
                            </div>
                        </div>
                    </div>
                    
                    <?php if (isset($errors['password'])): ?>
                        <p class="mt-2 text-sm text-danger-strong"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Confirm New Password -->
                <div x-data="{ showConfirmPassword: false }">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-heading">Konfirmasi Kata Sandi Baru</label>
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
            </div>

            <div class="space-y-4">
                <button 
                    type="submit" 
                    :disabled="loading || password !== passwordConfirmation || password.length < 8"
                    @click="loading = true"
                    class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-base text-white bg-brand hover:bg-brand-strong focus:outline-none focus:ring-4 focus:ring-brand-medium disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading" class="flex items-center">
                        <i class="ri-lock-unlock-line mr-2"></i>
                        Reset Kata Sandi
                    </span>
                    <span x-show="loading" class="flex items-center">
                        <i class="ri-loader-4-line animate-spin mr-2"></i>
                        Mereset kata sandi...
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
    function resetPasswordForm() {
        return {
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