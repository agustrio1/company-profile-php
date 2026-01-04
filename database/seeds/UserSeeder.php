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

    Database::execute(
        "INSERT INTO users (id, name, email, password, created_at, updated_at) 
         VALUES (:id, :name, :email, :password, :created_at, :updated_at)",
        $user
    );
    echo " User created: {$user['email']}\n";

    // Assign Super Admin Role to User
    Database::execute(
        "INSERT INTO role_user (user_id, role_id) VALUES (:user_id, :role_id)",
        ['user_id' => $userId, 'role_id' => $superAdminRole->id]
    );
    echo "✓ Super Admin role assigned\n";

    // Create Demo Company Profile
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

    Database::execute(
        "INSERT INTO company (id, name, slug, description, vision, mission, founded_year, address, phone, email, website, created_at, updated_at) 
         VALUES (:id, :name, :slug, :description, :vision, :mission, :founded_year, :address, :phone, :email, :website, :created_at, :updated_at)",
        $company
    );
    echo "✓ Demo company profile created\n";

    // Create Demo Blog Category
    $categoryId = strtolower(\Ulid\Ulid::generate());
    
    Database::execute(
        "INSERT INTO blog_categories (id, name, slug, description) 
         VALUES (:id, :name, :slug, :description)",
        [
            'id' => $categoryId,
            'name' => 'General',
            'slug' => 'general',
            'description' => 'General blog posts'
        ]
    );
    echo " Demo blog category created\n";

    Database::commit();
    echo "\n Users and demo data seeded successfully!\n";
    echo "\n Login credentials:\n";
    echo "   Email: admin@gmail.com\n";
    echo "   Password: password123\n\n";

} catch (Exception $e) {
    Database::rollback();
    echo "\n Error: " . $e->getMessage() . "\n";
    exit(1);
}