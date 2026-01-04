<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;
use App\Services\PasswordResetService;
use App\Validators\AuthValidator;

class AuthController
{
    private AuthService $authService;
    private PasswordResetService $resetService;
    private AuthValidator $validator;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->resetService = new PasswordResetService();
        $this->validator = new AuthValidator();
    }

    public function showLogin(): Response
    {
        return Response::make()->view('auth.login');
    }

    public function login(Request $request): Response
    {
        try {
            $data = $request->only(['email', 'password']);

            if (!$this->validator->validateLogin($data)) {
                return Response::make()
                    ->withErrors($this->validator->getErrors())
                    ->withInput()
                    ->back();
            }

            $result = $this->authService->login($data['email'], $data['password'], $request);

            if (!$result) {
                return Response::make()
                    ->withErrors(['email' => 'Email atau password salah'])
                    ->withInput()
                    ->back();
            }

            // Set auth cookie
            setcookie('auth_token', $result['session']->token, time() + (86400 * 30), '/', '', false, true);

            // Set session
            $_SESSION['auth_user'] = $result['user'];

            // Redirect to intended URL or dashboard
            $intendedUrl = $_SESSION['intended_url'] ?? url('admin/dashboard');
            unset($_SESSION['intended_url']);

            return Response::make()->redirect($intendedUrl);
            
        } catch (\Exception $e) {
            // Log error
            error_log('Login error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            return Response::make()
                ->with('error', 'Terjadi kesalahan saat login. Silakan coba lagi.')
                ->withInput()
                ->back();
        }
    }

    public function showRegister(): Response
    {
        return Response::make()->view('auth.register');
    }

    public function register(Request $request): Response
    {
        try {
            $data = $request->only(['name', 'email', 'password', 'password_confirmation']);

            if (!$this->validator->validateRegister($data)) {
                return Response::make()
                    ->withErrors($this->validator->getErrors())
                    ->withInput()
                    ->back();
            }

            $user = $this->authService->register($data);

            if (!$user) {
                return Response::make()
                    ->withErrors(['email' => 'Email sudah terdaftar'])
                    ->withInput()
                    ->back();
            }

            return Response::make()
                ->with('success', 'Registrasi berhasil! Silakan login.')
                ->redirect(url('login'));
                
        } catch (\Exception $e) {
            // Log error
            error_log('Register error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            return Response::make()
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.')
                ->withInput()
                ->back();
        }
    }

    public function logout(): Response
    {
        try {
            $token = $_COOKIE['auth_token'] ?? null;

            if ($token) {
                $this->authService->logout($token);
                setcookie('auth_token', '', time() - 3600, '/', '', false, true);
            }

            session_destroy();

            return Response::make()->redirect(url('login'));
            
        } catch (\Exception $e) {
            // Log error
            error_log('Logout error: ' . $e->getMessage());
            
            // Tetap redirect meskipun error
            return Response::make()->redirect(url('login'));
        }
    }

    public function showForgotPassword(): Response
    {
        return Response::make()->view('auth.forgot-password');
    }

    public function forgotPassword(Request $request): Response
    {
        try {
            $data = $request->only(['email']);

            if (!$this->validator->validateForgotPassword($data)) {
                return Response::make()
                    ->withErrors($this->validator->getErrors())
                    ->withInput()
                    ->back();
            }

            $this->resetService->requestReset($data['email']);

            return Response::make()
                ->with('success', 'Link reset password telah dikirim ke email Anda')
                ->back();
                
        } catch (\Exception $e) {
            // Log error
            error_log('Forgot password error: ' . $e->getMessage());
            
            return Response::make()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.')
                ->withInput()
                ->back();
        }
    }

    public function showResetPassword(Request $request): Response
    {
        try {
            $token = $request->query('token');

            if (!$token || !$this->resetService->validateToken($token)) {
                return Response::make()
                    ->with('error', 'Token reset tidak valid atau sudah kadaluarsa')
                    ->redirect(url('forgot-password'));
            }

            return Response::make()->view('auth.reset-password', ['token' => $token]);
            
        } catch (\Exception $e) {
            // Log error
            error_log('Show reset password error: ' . $e->getMessage());
            
            return Response::make()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.')
                ->redirect(url('forgot-password'));
        }
    }

    public function resetPassword(Request $request): Response
    {
        try {
            $data = $request->only(['token', 'password', 'password_confirmation']);

            if (!$this->validator->validateResetPassword($data)) {
                return Response::make()
                    ->withErrors($this->validator->getErrors())
                    ->withInput()
                    ->back();
            }

            if (!$this->resetService->resetPassword($data['token'], $data['password'])) {
                return Response::make()
                    ->withErrors(['token' => 'Token reset tidak valid atau sudah kadaluarsa'])
                    ->back();
            }

            return Response::make()
                ->with('success', 'Password berhasil direset! Silakan login.')
                ->redirect(url('login'));
                
        } catch (\Exception $e) {
            // Log error
            error_log('Reset password error: ' . $e->getMessage());
            
            return Response::make()
                ->with('error', 'Terjadi kesalahan saat reset password. Silakan coba lagi.')
                ->back();
        }
    }
}