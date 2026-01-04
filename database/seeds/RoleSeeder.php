<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use Dotenv\Dotenv;

// Load environment
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

echo "Seeding roles and permissions...\n";

try {
    // Get PDO connection directly
    $pdo = Database::connect();
    
    echo "→ Starting transaction...\n";
    $pdo->beginTransaction();
    echo "✓ Transaction started\n\n";

    // Generate ULIDs for roles
    echo "→ Generating ULIDs...\n";
    $superAdminRoleId = strtolower(\Ulid\Ulid::generate());
    $adminRoleId = strtolower(\Ulid\Ulid::generate());
    $editorRoleId = strtolower(\Ulid\Ulid::generate());
    
    echo "✓ ULIDs generated\n";
    echo "  Super Admin: {$superAdminRoleId}\n";
    echo "  Admin: {$adminRoleId}\n";
    echo "  Editor: {$editorRoleId}\n\n";

    // Insert Roles
    echo "→ Creating roles...\n";
    $roles = [
        ['id' => $superAdminRoleId, 'name' => 'Super Admin', 'description' => 'Full system access'],
        ['id' => $adminRoleId, 'name' => 'Admin', 'description' => 'Administrative access'],
        ['id' => $editorRoleId, 'name' => 'Editor', 'description' => 'Content management access']
    ];

    $roleStmt = $pdo->prepare("INSERT INTO roles (id, name, description) VALUES (:id, :name, :description)");
    foreach ($roles as $role) {
        $roleStmt->execute($role);
        echo "  ✓ {$role['name']}\n";
    }

    // Insert Permissions
    echo "\n→ Creating permissions...\n";
    $permissions = [
        ['name' => 'manage_users', 'description' => 'Create, edit, delete users'],
        ['name' => 'view_users', 'description' => 'View users list'],
        ['name' => 'manage_roles', 'description' => 'Create, edit, delete roles and permissions'],
        ['name' => 'view_roles', 'description' => 'View roles list'],
        ['name' => 'manage_blogs', 'description' => 'Create, edit, delete blogs'],
        ['name' => 'publish_blogs', 'description' => 'Publish/unpublish blogs'],
        ['name' => 'view_blogs', 'description' => 'View blogs list'],
        ['name' => 'manage_blog_categories', 'description' => 'Create, edit, delete blog categories'],
        ['name' => 'manage_services', 'description' => 'Create, edit, delete services'],
        ['name' => 'view_services', 'description' => 'View services list'],
        ['name' => 'manage_teams', 'description' => 'Create, edit, delete teams and employees'],
        ['name' => 'view_teams', 'description' => 'View teams list'],
        ['name' => 'manage_company', 'description' => 'Edit company profile'],
    ];

    $permissionIds = [];
    $permStmt = $pdo->prepare("INSERT INTO permissions (id, name, description) VALUES (:id, :name, :description)");
    
    foreach ($permissions as $permission) {
        $id = strtolower(\Ulid\Ulid::generate());
        $permissionIds[$permission['name']] = $id;
        
        $permStmt->execute([
            'id' => $id,
            'name' => $permission['name'],
            'description' => $permission['description']
        ]);
        
        echo "  ✓ {$permission['name']}\n";
    }

    // Assign permissions to roles
    echo "\n→ Assigning permissions...\n";
    $assignStmt = $pdo->prepare("INSERT INTO permission_role (role_id, permission_id) VALUES (:role_id, :permission_id)");

    // Super Admin gets all permissions
    echo "  → Super Admin (all permissions)...\n";
    foreach ($permissionIds as $permName => $permissionId) {
        $assignStmt->execute([
            'role_id' => $superAdminRoleId,
            'permission_id' => $permissionId
        ]);
    }
    echo "    ✓ " . count($permissionIds) . " permissions assigned\n";

    // Admin gets selected permissions
    echo "  → Admin (selected permissions)...\n";
    $adminPermissions = [
        'view_users', 'manage_blogs', 'publish_blogs', 'view_blogs',
        'manage_blog_categories', 'manage_services', 'view_services',
        'manage_teams', 'view_teams', 'manage_company'
    ];
    foreach ($adminPermissions as $permName) {
        $assignStmt->execute([
            'role_id' => $adminRoleId,
            'permission_id' => $permissionIds[$permName]
        ]);
    }
    echo "    ✓ " . count($adminPermissions) . " permissions assigned\n";

    // Editor gets selected permissions
    echo "  → Editor (selected permissions)...\n";
    $editorPermissions = [
        'manage_blogs', 'view_blogs', 'manage_blog_categories',
        'manage_services', 'view_services'
    ];
    foreach ($editorPermissions as $permName) {
        $assignStmt->execute([
            'role_id' => $editorRoleId,
            'permission_id' => $permissionIds[$permName]
        ]);
    }
    echo "    ✓ " . count($editorPermissions) . " permissions assigned\n";

    // Commit transaction
    $pdo->commit();
    echo "\n✅ Roles and permissions seeded successfully!\n";

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
        echo "\n🔄 Transaction rolled back\n";
    }
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}