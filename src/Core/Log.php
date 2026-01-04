<?php

namespace App\Core;

class Log
{
    private static ?string $logPath = null;
    private static bool $initialized = false;

    private static function init(): void
    {
        if (self::$initialized) {
            return;
        }

        $logDir = base_path('storage/logs');
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        self::$logPath = $logDir . '/app.log';
        self::$initialized = true;
    }

    private static function write(string $level, string $message, array $context = []): void
    {
        self::init();
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [{$level}] {$message}";
        
        if (!empty($context)) {
            $logMessage .= ' ' . json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        
        $logMessage .= PHP_EOL;
        
        file_put_contents(self::$logPath, $logMessage, FILE_APPEND);
    }

    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        self::write('DEBUG', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::write('WARNING', $message, $context);
    }

    public static function critical(string $message, array $context = []): void
    {
        self::write('CRITICAL', $message, $context);
    }

    public static function channel(string $channel): self
    {
        return new self();
    }

    public static function clear(): void
    {
        self::init();
        
        if (file_exists(self::$logPath)) {
            file_put_contents(self::$logPath, '');
        }
    }
}