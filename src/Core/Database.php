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
            // Check if DATABASE_URL is set (connection string format)
            if (!empty($_ENV['DATABASE_URL'])) {
                $url = $_ENV['DATABASE_URL'];
                
                // Parse PostgreSQL URL format
                // postgresql://user:password@host:port/database?sslmode=require
                $parsed = parse_url($url);
                
                if ($parsed === false) {
                    throw new PDOException("Invalid DATABASE_URL format");
                }
                
                $host = $parsed['host'] ?? 'localhost';
                $port = $parsed['port'] ?? 5432;
                $dbname = ltrim($parsed['path'] ?? '', '/');
                $user = $parsed['user'] ?? '';
                $password = $parsed['pass'] ?? '';
                
                // Parse query string for SSL mode
                $query = [];
                if (isset($parsed['query'])) {
                    parse_str($parsed['query'], $query);
                }
                $sslmode = $query['sslmode'] ?? 'prefer';
                
                // Build PDO DSN
                $dsn = sprintf(
                    "pgsql:host=%s;port=%s;dbname=%s;sslmode=%s",
                    $host, $port, $dbname, $sslmode
                );
                
                // Debug output in CLI mode
                if (php_sapi_name() === 'cli') {
                    echo "Connecting to database...\n";
                    echo "Host: {$host}\n";
                    echo "Port: {$port}\n";
                    echo "Database: {$dbname}\n";
                    echo "SSL Mode: {$sslmode}\n\n";
                }
                
                self::$connection = new PDO($dsn, $user, $password, self::$config['options']);
            } else {
                // Build DSN from separate parameters
                $dsn = sprintf(
                    "%s:host=%s;port=%s;dbname=%s",
                    self::$config['driver'],
                    self::$config['host'],
                    self::$config['port'],
                    self::$config['database']
                );

                // Add SSL mode
                if (!empty(self::$config['sslmode'])) {
                    $dsn .= ";sslmode=" . self::$config['sslmode'];
                }

                // Debug output in CLI mode
                if (php_sapi_name() === 'cli') {
                    echo "Connecting to database...\n";
                    echo "Host: " . self::$config['host'] . "\n";
                    echo "Port: " . self::$config['port'] . "\n";
                    echo "Database: " . self::$config['database'] . "\n\n";
                }

                self::$connection = new PDO(
                    $dsn,
                    self::$config['username'],
                    self::$config['password'],
                    self::$config['options']
                );
            }

            if (php_sapi_name() === 'cli') {
                echo "✓ Database connected successfully!\n\n";
            }

            return self::$connection;
        } catch (PDOException $e) {
            // Show detailed error in CLI mode or when debug is enabled
            if (php_sapi_name() === 'cli' || ($_ENV['APP_DEBUG'] ?? false)) {
                $errorMsg = "Database connection failed:\n";
                $errorMsg .= "Error: " . $e->getMessage() . "\n";
                $errorMsg .= "Code: " . $e->getCode() . "\n";
                
                if (!empty($_ENV['DATABASE_URL'])) {
                    $parsed = parse_url($_ENV['DATABASE_URL']);
                    $errorMsg .= "\nConnection details:\n";
                    $errorMsg .= "Host: " . ($parsed['host'] ?? 'unknown') . "\n";
                    $errorMsg .= "Port: " . ($parsed['port'] ?? '5432') . "\n";
                    $errorMsg .= "Database: " . ltrim($parsed['path'] ?? '', '/') . "\n";
                }
                
                throw new \RuntimeException($errorMsg);
            }
            
            throw new \RuntimeException('Database connection failed');
        }
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
        return self::connect()->rollBack();
    }
}