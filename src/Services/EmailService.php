<?php

namespace App\Services;

class EmailService
{
    private string $apiKey;
    private string $fromEmail;
    private string $fromName;

    public function __construct()
    {
        $this->apiKey = env('RESEND_API_KEY');
        $this->fromEmail = env('MAIL_FROM_ADDRESS', 'noreply@example.com');
        $this->fromName = env('MAIL_FROM_NAME', config('app.name'));
    }

    public function send(string $to, string $subject, string $html): bool
    {
        $subject = trim(preg_replace('/\s+/', ' ', $subject));

        $payload = [
            'from' => $this->fromEmail,
            'to' => [$to],
            'subject' => $subject,
            'html' => $html,
        ];

        $ch = curl_init('https://api.resend.com/emails');

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 20,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return true;
        }

        // Log error if needed
        if (config('app.debug')) {
            error_log("Email send failed: " . $response);
        }

        return false;
    }

    public function sendForgotPassword(string $to, string $name, string $resetUrl): bool
    {
        $subject = 'Reset Password - ' . config('app.name');
        
        $html = $this->renderForgotPasswordTemplate($name, $resetUrl);

        return $this->send($to, $subject, $html);
    }

    public function sendPasswordResetSuccess(string $to, string $name): bool
    {
        $subject = 'Password Reset Successful - ' . config('app.name');
        
        $html = $this->renderPasswordResetSuccessTemplate($name);

        return $this->send($to, $subject, $html);
    }

    public function sendWelcome(string $to, string $name): bool
    {
        $subject = 'Welcome to ' . config('app.name');
        
        $html = $this->renderWelcomeTemplate($name);

        return $this->send($to, $subject, $html);
    }

    private function renderForgotPasswordTemplate(string $name, string $resetUrl): string
    {
        return "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .button { display: inline-block; padding: 12px 24px; background: #3b82f6; color: #fff; text-decoration: none; border-radius: 6px; }
                    .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Reset Your Password</h2>
                    <p>Hi {$name},</p>
                    <p>We received a request to reset your password. Click the button below to reset it:</p>
                    <p><a href='{$resetUrl}' class='button'>Reset Password</a></p>
                    <p>This link will expire in 1 hour.</p>
                    <p>If you didn't request this, please ignore this email.</p>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " " . config('app.name') . ". All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        ";
    }

    private function renderPasswordResetSuccessTemplate(string $name): string
    {
        return "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .success { color: #10b981; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2 class='success'>✓ Password Reset Successful</h2>
                    <p>Hi {$name},</p>
                    <p>Your password has been successfully reset.</p>
                    <p>If you didn't make this change, please contact us immediately.</p>
                </div>
            </body>
            </html>
        ";
    }

    private function renderWelcomeTemplate(string $name): string
    {
        return "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Welcome to " . config('app.name') . "!</h2>
                    <p>Hi {$name},</p>
                    <p>Thank you for registering with us. We're excited to have you on board!</p>
                </div>
            </body>
            </html>
        ";
    }
}