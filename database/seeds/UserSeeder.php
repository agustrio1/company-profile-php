<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use Dotenv\Dotenv;

// Load environment
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

// Connect to database
Database::connect();

echo "Seeding users...\n";

try {
    Database::beginTransaction();

    // Get Super Admin Role
    $superAdminRole = Database::fetchOne("SELECT id FROM roles WHERE name = 'Super Admin'");
    
    if (!$superAdminRole) {
        throw new Exception("Super Admin role not found. Please run RoleSeeder first.");
    }
    echo "✓ Super Admin role found: {$superAdminRole->id}\n";

    // Create Super Admin User
    $userId = strtolower(\Ulid\Ulid::generate());
    $now = date('Y-m-d H:i:s');

    $user = [
        'id' => $userId,
        'name' => 'Super Admin',
        'email' => 'admin@gmail.com',
        'password' => password_hash('password123', PASSWORD_BCRYPT),
        'created_at' => $now,
        'updated_at' => $now
    ];

    try {
        Database::query(
            "INSERT INTO users (id, name, email, password, created_at, updated_at) 
             VALUES (:id, :name, :email, :password, :created_at, :updated_at)",
            $user
        );
        echo "✓ User created: {$user['email']}\n";
    } catch (Exception $e) {
        throw new Exception("Failed to create user: " . $e->getMessage());
    }

    // Assign Super Admin Role to User
    try {
        Database::query(
            "INSERT INTO role_user (user_id, role_id) VALUES (:user_id, :role_id)",
            ['user_id' => $userId, 'role_id' => $superAdminRole->id]
        );
        echo "✓ Super Admin role assigned\n";
    } catch (Exception $e) {
        throw new Exception("Failed to assign role: " . $e->getMessage());
    }

    // Create Demo Company Profile
    echo "→ Creating demo company profile...\n";
    $companyId = strtolower(\Ulid\Ulid::generate());
    
    $company = [
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
        'website' => 'https://example.com',
        'created_at' => $now,
        'updated_at' => $now
    ];

    try {
        Database::query(
            "INSERT INTO company (id, name, slug, description, vision, mission, founded_year, address, phone, email, website, created_at, updated_at) 
             VALUES (:id, :name, :slug, :description, :vision, :mission, :founded_year, :address, :phone, :email, :website, :created_at, :updated_at)",
            $company
        );
        echo "✓ Demo company profile created\n";
    } catch (Exception $e) {
        throw new Exception("Failed to create company: " . $e->getMessage());
    }

    // Create Demo Blog Category
    echo "→ Creating demo blog category...\n";
    $categoryId = strtolower(\Ulid\Ulid::generate());
    
    try {
        Database::query(
            "INSERT INTO blog_categories (id, name, slug, description) 
             VALUES (:id, :name, :slug, :description)",
            [
                'id' => $categoryId,
                'name' => 'General',
                'slug' => 'general',
                'description' => 'General blog posts'
            ]
        );
        echo "✓ Demo blog category created\n";
    } catch (Exception $e) {
        throw new Exception("Failed to create category: " . $e->getMessage());
    }

    Database::commit();
    echo "\n✅ Users and demo data seeded successfully!\n\n";
    echo "===========================================\n";
    echo "  LOGIN CREDENTIALS\n";
    echo "===========================================\n";
    echo "Email:    admin@gmail.com\n";
    echo "Password: password123\n";
    echo "===========================================\n\n";
    echo "⚠️  Please change the password after first login!\n\n";

} catch (Exception $e) {
    Database::rollback();
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}