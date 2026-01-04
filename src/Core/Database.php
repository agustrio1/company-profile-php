<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;
    private static array $config = [];

    public static function connect(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        self::$config = require config_path('database.php');

        try {
            // Priority: DATABASE_URL > Individual params
            if (!empty($_ENV['DATABASE_URL'])) {
                self::$connection = self::connectWithUrl($_ENV['DATABASE_URL']);
            } else {
                self::$connection = self::connectWithParams();
            }

            if (php_sapi_name() === 'cli') {
                echo "✓ Database connected successfully!\n";
                echo "-------------------------------------------\n\n";
            }

            return self::$connection;
        } catch (PDOException $e) {
            self::handleConnectionError($e);
        }
    }

    private static function connectWithUrl(string $url): PDO
    {
        $parsed = parse_url($url);
        
        if ($parsed === false) {
            throw new PDOException("Invalid DATABASE_URL format");
        }
        
        $host = $parsed['host'] ?? 'localhost';
        $port = $parsed['port'] ?? 5432;
        $dbname = ltrim($parsed['path'] ?? '', '/');
        $user = $parsed['user'] ?? '';
        $password = $parsed['pass'] ?? '';
        
        // Parse query string
        $query = [];
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }
        $sslmode = $query['sslmode'] ?? 'prefer';
        
        // Extract endpoint ID for NeonDB (if hostname contains endpoint pattern)
        $endpointId = self::extractEndpointId($host);
        
        // Build DSN
        $dsn = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s;sslmode=%s",
            $host, $port, $dbname, $sslmode
        );
        
        // Add endpoint parameter if detected (for NeonDB)
        if ($endpointId) {
            $dsn .= ";options='endpoint={$endpointId}'";
        }
        
        if (php_sapi_name() === 'cli') {
            echo "Connecting via DATABASE_URL...\n";
            
            // Show details only in debug mode
            if ($_ENV['APP_DEBUG'] ?? false) {
                echo "Host: {$host}\n";
                echo "Port: {$port}\n";
                echo "Database: {$dbname}\n";
                echo "SSL Mode: {$sslmode}\n";
                if ($endpointId) {
                    echo "Endpoint: {$endpointId}\n";
                }
            }
            echo "\n";
        }
        
        return new PDO($dsn, $user, $password, self::$config['options']);
    }

    private static function connectWithParams(): PDO
    {
        $host = self::$config['host'];
        $port = self::$config['port'];
        $dbname = self::$config['database'];
        $user = self::$config['username'];
        $password = self::$config['password'];
        $sslmode = self::$config['sslmode'] ?? 'prefer';
        
        // Extract endpoint ID for NeonDB
        $endpointId = self::extractEndpointId($host);
        
        // Build DSN
        $dsn = sprintf(
            "%s:host=%s;port=%s;dbname=%s;sslmode=%s",
            self::$config['driver'],
            $host, $port, $dbname, $sslmode
        );
        
        // Add endpoint parameter if detected (for NeonDB)
        if ($endpointId) {
            $dsn .= ";options='endpoint={$endpointId}'";
        }
        
        if (php_sapi_name() === 'cli') {
            echo "Connecting via individual params...\n";
            
            // Show details only in debug mode
            if ($_ENV['APP_DEBUG'] ?? false) {
                echo "Host: {$host}\n";
                echo "Port: {$port}\n";
                echo "Database: {$dbname}\n";
                echo "SSL Mode: {$sslmode}\n";
                if ($endpointId) {
                    echo "Endpoint: {$endpointId}\n";
                }
            }
            echo "\n";
        }
        
        return new PDO($dsn, $user, $password, self::$config['options']);
    }

    private static function extractEndpointId(string $host): ?string
    {
        // Check if host matches NeonDB pattern (ep-xxx-xxx.region.aws.neon.tech)
        if (preg_match('/^(ep-[a-z0-9-]+)(?:-pooler)?\./', $host, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    private static function handleConnectionError(PDOException $e): void
    {
        // Show detailed error ONLY in CLI mode AND debug enabled
        $showDetails = php_sapi_name() === 'cli' && ($_ENV['APP_DEBUG'] ?? false) === true;
        
        if ($showDetails) {
            $errorMsg = "Database connection failed:\n";
            $errorMsg .= "Error: " . $e->getMessage() . "\n";
            $errorMsg .= "Code: " . $e->getCode() . "\n";
            
            if (!empty($_ENV['DATABASE_URL'])) {
                $parsed = parse_url($_ENV['DATABASE_URL']);
                $errorMsg .= "\nConnection details (from DATABASE_URL):\n";
                $errorMsg .= "Host: " . ($parsed['host'] ?? 'unknown') . "\n";
                $errorMsg .= "Port: " . ($parsed['port'] ?? '5432') . "\n";
                $errorMsg .= "Database: " . ltrim($parsed['path'] ?? '', '/') . "\n";
                // JANGAN tampilkan username & password!
            } else {
                $errorMsg .= "\nConnection details (from params):\n";
                $errorMsg .= "Host: " . (self::$config['host'] ?? 'unknown') . "\n";
                $errorMsg .= "Port: " . (self::$config['port'] ?? '5432') . "\n";
                $errorMsg .= "Database: " . (self::$config['database'] ?? 'unknown') . "\n";
            }
            
            throw new \RuntimeException($errorMsg);
        }
        
        // Production: Generic error only
        error_log("Database connection failed: " . $e->getMessage());
        throw new \RuntimeException('Database connection failed. Please contact administrator.');
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $connection = self::connect();
        $statement = $connection->prepare($sql);
        $statement->execute($params);
        
        return $statement;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetchOne(string $sql, array $params = []): ?object
    {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    public static function execute(string $sql, array $params = []): bool
    {
        return self::query($sql, $params)->rowCount() > 0;
    }

    public static function lastInsertId(): string
    {
        return self::connect()->lastInsertId();
    }

    public static function beginTransaction(): bool
    {
        return self::connect()->beginTransaction();
    }

    public static function commit(): bool
    {
        return self::connect()->commit();
    }

    public static function rollback(): bool
    {
        // Check if transaction is active before rollback
        if (self::connect()->inTransaction()) {
            return self::connect()->rollBack();
        }
        return false;
    }
}