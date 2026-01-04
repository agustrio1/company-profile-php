<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use Dotenv\Dotenv;

// Load environment
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

echo "Seeding users and demo data...\n";

try {
    // Get fresh PDO connection
    $pdo = Database::connect();
    
    // Start new transaction
    $pdo->beginTransaction();

    // Get Super Admin Role
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = :name LIMIT 1");
    $stmt->execute(['name' => 'Super Admin']);
    $superAdminRole = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$superAdminRole) {
        throw new Exception("Super Admin role not found. Please run RoleSeeder first.");
    }
    
    echo "✓ Super Admin role found: {$superAdminRole['id']}\n\n";

    // Create Super Admin User
    echo "→ Creating super admin user...\n";
    $userId = strtolower(\Ulid\Ulid::generate());

    $userStmt = $pdo->prepare(
        "INSERT INTO users (id, name, email, password, created_at, updated_at) 
         VALUES (:id, :name, :email, :password, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
    );

    $userStmt->execute([
        'id' => $userId,
        'name' => 'Super Admin',
        'email' => 'admin@gmail.com',
        'password' => password_hash('password123', PASSWORD_BCRYPT)
    ]);
    
    echo "  ✓ User created: admin@gmail.com\n";

    // Assign Super Admin Role to User
    $roleUserStmt = $pdo->prepare(
        "INSERT INTO role_user (user_id, role_id) VALUES (:user_id, :role_id)"
    );
    
    $roleUserStmt->execute([
        'user_id' => $userId,
        'role_id' => $superAdminRole['id']
    ]);
    
    echo "  ✓ Super Admin role assigned\n";

    // Create Demo Company Profile
    echo "\n→ Creating demo company profile...\n";
    $companyId = strtolower(\Ulid\Ulid::generate());
    
    $companyStmt = $pdo->prepare(
        "INSERT INTO company (id, name, slug, description, vision, mission, founded_year, address, phone, email, website, created_at, updated_at) 
         VALUES (:id, :name, :slug, :description, :vision, :mission, :founded_year, :address, :phone, :email, :website, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
    );

    $companyStmt->execute([
        'id' => $companyId,
        'name' => 'Your Company Name',
        'slug' => 'your-company-name',
        'description' => 'Your company description here',
        'vision' => 'Your company vision here',
        'mission' => 'Your company mission here',
        'founded_year' => 2024,
        'address' => 'Your company address',
        'phone' => '+1234567890',
        'email' => 'info@example.com',
        'website' => 'https://example.com'
    ]);
    
    echo "  ✓ Demo company profile created\n";

    // Create Demo Blog Category
    echo "\n→ Creating demo blog category...\n";
    $categoryId = strtolower(\Ulid\Ulid::generate());
    
    $categoryStmt = $pdo->prepare(
        "INSERT INTO blog_categories (id, name, slug, description) 
         VALUES (:id, :name, :slug, :description)"
    );

    $categoryStmt->execute([
        'id' => $categoryId,
        'name' => 'General',
        'slug' => 'general',
        'description' => 'General blog posts'
    ]);
    
    echo "  ✓ Demo blog category created\n";

    // Commit transaction
    $pdo->commit();
    
    echo "\n✅ Users and demo data seeded successfully!\n\n";
    echo "===========================================\n";
    echo "  LOGIN CREDENTIALS\n";
    echo "===========================================\n";
    echo "Email:    admin@gmail.com\n";
    echo "Password: password123\n";
    echo "===========================================\n\n";
    echo "⚠️  Please change the password after first login!\n\n";

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
        echo "\n🔄 Transaction rolled back\n";
    }
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}