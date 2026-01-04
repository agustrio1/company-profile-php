<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use Dotenv\Dotenv;

// Load environment
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

echo "Seeding users...\n";

try {
    // Get fresh PDO connection
    $pdo = Database::connect();
    
    // Start new transaction
    $pdo->beginTransaction();

    // Find Super Admin role
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = :name LIMIT 1");
    $stmt->execute(['name' => 'Super Admin']);
    $superAdminRole = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$superAdminRole) {
        throw new Exception("Super Admin role not found. Please run RoleSeeder first.");
    }
    
    echo "✓ Super Admin role found: {$superAdminRole['id']}\n\n";

    // Create default users
    echo "→ Creating users...\n";
    
    $users = [
        [
            'id' => strtolower(\Ulid\Ulid::generate()),
            'name' => 'Super Admin',
            'email' => 'superadmin@nexarostudio.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'role_id' => $superAdminRole['id']
        ],
        [
            'id' => strtolower(\Ulid\Ulid::generate()),
            'name' => 'Admin User',
            'email' => 'admin@nexarostudio.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'role_id' => null // Will be set to Admin role
        ],
        [
            'id' => strtolower(\Ulid\Ulid::generate()),
            'name' => 'Editor User',
            'email' => 'editor@nexarostudio.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'role_id' => null // Will be set to Editor role
        ]
    ];

    // Get Admin and Editor role IDs
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = :name LIMIT 1");
    
    $stmt->execute(['name' => 'Admin']);
    $adminRole = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($adminRole) {
        $users[1]['role_id'] = $adminRole['id'];
    }
    
    $stmt->execute(['name' => 'Editor']);
    $editorRole = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($editorRole) {
        $users[2]['role_id'] = $editorRole['id'];
    }

    // Insert users
    $userStmt = $pdo->prepare(
        "INSERT INTO users (id, name, email, password, created_at, updated_at) 
         VALUES (:id, :name, :email, :password, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
    );

    // Assign roles to users
    $roleUserStmt = $pdo->prepare(
        "INSERT INTO role_user (user_id, role_id) VALUES (:user_id, :role_id)"
    );

    foreach ($users as $user) {
        // Insert user
        $userStmt->execute([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password']
        ]);
        
        echo "  ✓ User created: {$user['name']} ({$user['email']})\n";

        // Assign role if exists
        if ($user['role_id']) {
            $roleUserStmt->execute([
                'user_id' => $user['id'],
                'role_id' => $user['role_id']
            ]);
            echo "    → Role assigned\n";
        }
    }

    // Commit transaction
    $pdo->commit();
    
    echo "\n✅ Users seeded successfully!\n";
    echo "\nDefault Credentials:\n";
    echo "-------------------\n";
    echo "Super Admin: superadmin@nexarostudio.com / password123\n";
    echo "Admin:       admin@nexarostudio.com / password123\n";
    echo "Editor:      editor@nexarostudio.com / password123\n";

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
        echo "\n🔄 Transaction rolled back\n";
    }
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}