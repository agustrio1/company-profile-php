<?php

namespace App\Core;

class Vite
{
    private static $manifest = null;
    private static $devServerUrl = 'http://localhost:5173';
    
    public static function assets(): string
    {
        if (self::isRunningHot()) {
            return self::makeDevTags();
        }
        
        return self::makeProdTags();
    }
    
    private static function isRunningHot(): bool
    {
        $hotFile = __DIR__ . '/../../public/hot';
        return file_exists($hotFile) && getenv('APP_ENV') !== 'production';
    }
    
    private static function makeDevTags(): string
    {
        return sprintf(
            '<script type="module" src="%s/@vite/client"></script>' . PHP_EOL .
            '<script type="module" src="%s/resources/js/app.js"></script>',
            self::$devServerUrl,
            self::$devServerUrl
        );
    }
    
    private static function makeProdTags(): string
    {
        $manifest = self::getManifest();
        
        if (!$manifest) {
            return '';
        }
        
        $output = '';
        
        if (isset($manifest['resources/js/app.js'])) {
            $entry = $manifest['resources/js/app.js'];
            
            if (isset($entry['css'])) {
                foreach ($entry['css'] as $cssFile) {
                    $output .= sprintf('<link rel="stylesheet" href="/build/%s">' . PHP_EOL, $cssFile);
                }
            }
            
            if (isset($entry['file'])) {
                $output .= sprintf('<script type="module" src="/build/%s"></script>', $entry['file']);
            }
        }
        
        return $output;
    }
    
    private static function getManifest(): ?array
    {
        if (self::$manifest !== null) {
            return self::$manifest;
        }
        
        $manifestPath = __DIR__ . '/../../public/build/manifest.json';
        
        if (!file_exists($manifestPath)) {
            return null;
        }
        
        self::$manifest = json_decode(file_get_contents($manifestPath), true);
        
        return self::$manifest;
    }
    
    public static function asset(string $path): string
    {
        if (self::isRunningHot()) {
            return self::$devServerUrl . '/' . ltrim($path, '/');
        }
        
        $manifest = self::getManifest();
        
        if ($manifest && isset($manifest[$path]['file'])) {
            return '/build/' . $manifest[$path]['file'];
        }
        
        return '/' . ltrim($path, '/');
    }
}