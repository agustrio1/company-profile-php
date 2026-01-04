<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Database;
use Dotenv\Dotenv;

// Load environment
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Connect to database
Database::connect();

echo "===========================================\n";
echo "  Database Migration Tool\n";
echo "===========================================\n\n";

$command = $argv[1] ?? 'migrate';

switch ($command) {
    case 'migrate':
        runMigrations();
        break;
    
    case 'fresh':
        echo "⚠️  This will DROP all tables and re-run migrations.\n";
        echo "Are you sure? (yes/no): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (trim($line) !== 'yes') {
            echo "Migration cancelled.\n";
            exit;
        }
        dropAllTables();
        runMigrations();
        break;
    
    case 'seed':
        runSeeders();
        break;
    
    case 'fresh-seed':
        echo "⚠️  This will DROP all tables, re-run migrations, and seed data.\n";
        echo "Are you sure? (yes/no): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (trim($line) !== 'yes') {
            echo "Migration cancelled.\n";
            exit;
        }
        dropAllTables();
        runMigrations();
        runSeeders();
        break;
    
    case 'rollback':
        rollbackMigrations();
        break;
    
    case 'status':
        showMigrationStatus();
        break;
    
    default:
        showHelp();
        break;
}

function runMigrations()
{
    echo "Running migrations...\n\n";
    
    $migrationsPath = __DIR__ . '/database/migrations';
    $files = glob($migrationsPath . '/*.sql');
    
    if (empty($files)) {
        echo "No migration files found.\n";
        return;
    }
    
    sort($files);
    
    // Jalankan migration pertama dulu (yang biasanya buat migrations table)
    $firstFile = $files[0];
    $firstName = basename($firstFile);
    
    try {
        echo "→ Running: {$firstName}...";
        
        $sql = file_get_contents($firstFile);
        Database::connect()->exec($sql);
        
        echo " ✓\n";
    } catch (Exception $e) {
        // Jika error karena table sudah ada, skip
        if (strpos($e->getMessage(), 'already exists') === false) {
            echo " ✗\n";
            echo "   Error: " . $e->getMessage() . "\n";
            exit(1);
        } else {
            echo " ⊘ (already exists)\n";
        }
    }
    
    // Sekarang migrations table pasti ada, cek dan jalankan sisanya
    $currentBatch = getCurrentBatch() + 1;
    $ranMigrations = 0;
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        // Check if migration already run
        if (isMigrationRun($filename)) {
            echo "⊘ Skipped: {$filename} (already run)\n";
            continue;
        }
        
        // Skip first file karena sudah dijalankan di atas
        if ($filename === $firstName) {
            recordMigration($filename, $currentBatch);
            $ranMigrations++;
            continue;
        }
        
        try {
            echo "→ Running: {$filename}...";
            
            $sql = file_get_contents($file);
            Database::connect()->exec($sql);
            
            // Record migration
            recordMigration($filename, $currentBatch);
            
            echo " ✓\n";
            $ranMigrations++;
            
        } catch (Exception $e) {
            echo " ✗\n";
            echo "   Error: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
    
    if ($ranMigrations === 0) {
        echo "\n✅ Nothing to migrate. All migrations are up to date.\n\n";
    } else {
        echo "\n✅ {$ranMigrations} migration(s) completed successfully!\n\n";
    }
}

function ensureMigrationsTable()
{
    try {
        // Check if migrations table exists
        $check = Database::query("
            SELECT EXISTS (
                SELECT FROM information_schema.tables 
                WHERE table_schema = 'public' 
                AND table_name = 'migrations'
            ) as exists
        ")->fetch();
        
        if (!$check || $check->exists === false || $check->exists === 'f') {
            echo "Creating migrations tracking table...\n";
            
            Database::connect()->exec("
                CREATE TABLE IF NOT EXISTS migrations (
                    id SERIAL PRIMARY KEY,
                    migration VARCHAR(255) NOT NULL UNIQUE,
                    batch INTEGER NOT NULL,
                    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            echo "✓ Migrations table created\n\n";
        }
    } catch (Exception $e) {
        // Table might exist, continue
    }
}

function isMigrationRun($filename)
{
    try {
        $exists = Database::fetchOne(
            "SELECT id FROM migrations WHERE migration = :migration",
            ['migration' => $filename]
        );
        
        return $exists !== null;
    } catch (Exception $e) {
        return false;
    }
}

function getCurrentBatch()
{
    try {
        $result = Database::fetchOne("SELECT MAX(batch) as batch FROM migrations");
        return $result && $result->batch ? (int)$result->batch : 0;
    } catch (Exception $e) {
        return 0;
    }
}

function recordMigration($filename, $batch)
{
    try {
        Database::execute(
            "INSERT INTO migrations (migration, batch, executed_at) VALUES (:migration, :batch, CURRENT_TIMESTAMP)",
            [
                'migration' => $filename,
                'batch' => $batch
            ]
        );
    } catch (Exception $e) {
        echo "Error recording migration: " . $e->getMessage() . "\n";
        exit(1);
    }
}

function dropAllTables()
{
    echo "Dropping all tables...\n";
    
    try {
        $tables = Database::query("
            SELECT tablename 
            FROM pg_tables 
            WHERE schemaname = 'public'
        ")->fetchAll();
        
        Database::connect()->exec('DROP SCHEMA public CASCADE');
        Database::connect()->exec('CREATE SCHEMA public');
        Database::connect()->exec('GRANT ALL ON SCHEMA public TO public');
        
        echo "✓ All tables dropped\n\n";
        
    } catch (Exception $e) {
        echo "Error dropping tables: " . $e->getMessage() . "\n";
        exit(1);
    }
}

function rollbackMigrations()
{
    echo "Rolling back last migration batch...\n\n";
    
    try {
        // Ensure migrations table exists
        ensureMigrationsTable();
        
        // Get last batch
        $lastBatch = Database::fetchOne("SELECT MAX(batch) as batch FROM migrations");
        
        if (!$lastBatch || !$lastBatch->batch) {
            echo "No migrations to rollback.\n";
            return;
        }
        
        // Get migrations from last batch
        $migrations = Database::query(
            "SELECT * FROM migrations WHERE batch = :batch ORDER BY executed_at DESC",
            ['batch' => $lastBatch->batch]
        )->fetchAll();
        
        if (empty($migrations)) {
            echo "No migrations found in last batch.\n";
            return;
        }
        
        echo "⚠️  Warning: Manual rollback required.\n";
        echo "The following migrations need to be rolled back manually:\n\n";
        
        foreach ($migrations as $migration) {
            echo "  - {$migration->migration} (executed at: {$migration->executed_at})\n";
        }
        
        echo "\nAfter manual rollback, run this to remove records:\n";
        echo "DELETE FROM migrations WHERE batch = {$lastBatch->batch};\n\n";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}

function runSeeders()
{
    echo "\n===========================================\n";
    echo "  Running Seeders\n";
    echo "===========================================\n\n";
    
    $seeders = [
        __DIR__ . '/database/seeds/RoleSeeder.php',
        __DIR__ . '/database/seeds/UserSeeder.php'
    ];
    
    foreach ($seeders as $seeder) {
        if (file_exists($seeder)) {
            echo "Running: " . basename($seeder) . "\n";
            echo "-------------------------------------------\n";
            require $seeder;
            echo "\n";
        }
    }
    
    echo "✅ All seeders completed!\n\n";
}

function showMigrationStatus()
{
    echo "Migration Status:\n\n";
    
    try {
        // Ensure migrations table exists
        ensureMigrationsTable();
        
        $migrations = Database::query("
            SELECT migration, batch, executed_at 
            FROM migrations 
            ORDER BY batch ASC, executed_at ASC
        ")->fetchAll();
        
        if (empty($migrations)) {
            echo "No migrations have been run yet.\n";
            return;
        }
        
        echo sprintf("%-50s %-10s %-20s\n", "Migration", "Batch", "Executed At");
        echo str_repeat("-", 80) . "\n";
        
        foreach ($migrations as $migration) {
            echo sprintf(
                "%-50s %-10s %-20s\n",
                $migration->migration,
                $migration->batch,
                date('Y-m-d H:i:s', strtotime($migration->executed_at))
            );
        }
        
        echo "\n";
        
        // Show pending migrations
        $migrationsPath = __DIR__ . '/database/migrations';
        $files = glob($migrationsPath . '/*.sql');
        
        if (!empty($files)) {
            $pending = [];
            foreach ($files as $file) {
                $filename = basename($file);
                if (!isMigrationRun($filename)) {
                    $pending[] = $filename;
                }
            }
            
            if (!empty($pending)) {
                echo "Pending Migrations:\n";
                echo str_repeat("-", 80) . "\n";
                foreach ($pending as $file) {
                    echo "  - {$file}\n";
                }
                echo "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

function showHelp()
{
    echo "Usage: php migrate.php [command]\n\n";
    echo "Commands:\n";
    echo "  migrate       Run pending migrations (skips already run migrations)\n";
    echo "  fresh         Drop all tables and re-run all migrations\n";
    echo "  seed          Run database seeders\n";
    echo "  fresh-seed    Fresh migration + seed\n";
    echo "  rollback      Rollback last migration batch\n";
    echo "  status        Show migration status (run & pending)\n";
    echo "  help          Show this help message\n\n";
    echo "Examples:\n";
    echo "  php migrate.php migrate       # Run only new migrations\n";
    echo "  php migrate.php status        # Check which migrations ran\n";
    echo "  php migrate.php fresh-seed    # Reset everything\n\n";
}