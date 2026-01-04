<?php
$title = 'Masuk';
ob_start();
?>

<div class="min-h-screen flex">
    <!-- Left Side - Form -->
    <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-20 xl:px-24 bg-white">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-3xl text-center font-bold text-gray-900">
                    Masuk ke Akun Anda
                </h2>
               <!--  <p class="mt-2 text-sm text-gray-600">
                   Belum punya akun?
                   <a href="/register" class="font-medium text-blue-600 hover:text-blue-700">
                       Daftar sekarang
                   </a>
               </p> -->
            </div>

            <?php if (session('error')): ?>
                <div class="p-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg" role="alert">
                    <span class="font-medium">Error!</span> <?= session('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session('success')): ?>
                <div class="p-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg" role="alert">
                    <span class="font-medium">Berhasil!</span> <?= session('success') ?>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6" action="/login" method="POST" x-data="loginForm()" @submit.prevent="handleSubmit">
                <?= csrf_field() ?>
                
                <div class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            x-model="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= isset($errors['email']) ? 'border-red-500' : '' ?>" 
                            placeholder="nama@perusahaan.com" 
                            value="<?= old('email') ?>"
                            :disabled="loading"
                            required
                        >
                        <?php if (isset($errors['email'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                id="password" 
                                x-model="password"
                                placeholder="••••••••" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= isset($errors['password']) ? 'border-red-500' : '' ?>" 
                                :disabled="loading"
                                required
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                :disabled="loading"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            :disabled="loading"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>

                   <!--  <a href="/forgot-password" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                       Lupa password?
                   </a> -->
                </div>

                <button 
                    type="submit" 
                    :disabled="loading"
                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    <span x-show="!loading">Masuk</span>
                    <span x-show="loading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sedang masuk...
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- Right Side - Illustration (Hidden on mobile) -->
    <div class="hidden lg:flex flex-1 bg-blue-600 items-center justify-center p-12">
        <div class="max-w-md text-center">
            <svg class="w-full h-auto mb-8" viewBox="0 0 500 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background Circle -->
                <circle cx="250" cy="200" r="180" fill="rgba(255,255,255,0.1)"/>
                
                <!-- Laptop -->
                <rect x="150" y="140" width="200" height="140" rx="8" fill="white"/>
                <rect x="160" y="150" width="180" height="110" fill="#E5E7EB"/>
                <rect x="230" y="280" width="40" height="15" rx="2" fill="white"/>
                <rect x="130" y="295" width="240" height="8" rx="4" fill="white"/>
                
                <!-- Screen Content -->
                <circle cx="250" cy="205" r="30" fill="#3B82F6"/>
                <path d="M240 205L248 213L260 197" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                
                <!-- Lock Icon -->
                <rect x="350" y="80" width="60" height="70" rx="6" fill="white" opacity="0.9"/>
                <rect x="360" y="100" width="40" height="40" rx="4" fill="#3B82F6"/>
                <circle cx="380" cy="120" r="8" stroke="white" stroke-width="3" fill="none"/>
                <rect x="376" y="120" width="8" height="12" fill="white"/>
            </svg>
            
            <h2 class="text-3xl font-bold text-white mb-4">
                Selamat Datang Kembali
            </h2>
            <p class="text-blue-100 text-lg">
                Kelola bisnis Anda dengan mudah dan efisien
            </p>
        </div>
    </div>
</div>

<script>
    function loginForm() {
        return {
            email: '<?= old('email') ?>',
            password: '',
            loading: false,
            
            handleSubmit(event) {
                this.loading = true;
                event.target.submit();
                
                setTimeout(() => {
                    this.loading = false;
                }, 5000);
            }
        }
    }
</script>

<?php
$content = ob_get_clean();
require view_path('layouts/guest.php');
?>