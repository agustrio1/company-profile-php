<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use Dotenv\Dotenv;

// Load environment
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

// Connect to database
Database::connect();

echo "Seeding roles and permissions...\n";

try {
    Database::beginTransaction();

    // Generate ULIDs
    $superAdminRoleId = strtolower(\Ulid\Ulid::generate());
    $adminRoleId = strtolower(\Ulid\Ulid::generate());
    $editorRoleId = strtolower(\Ulid\Ulid::generate());

    // Insert Roles
    $roles = [
        ['id' => $superAdminRoleId, 'name' => 'Super Admin', 'description' => 'Full system access'],
        ['id' => $adminRoleId, 'name' => 'Admin', 'description' => 'Administrative access'],
        ['id' => $editorRoleId, 'name' => 'Editor', 'description' => 'Content management access']
    ];

    foreach ($roles as $role) {
        Database::execute(
            "INSERT INTO roles (id, name, description) VALUES (:id, :name, :description)",
            $role
        );
        echo "✓ Role created: {$role['name']}\n";
    }

    // Insert Permissions
    $permissions = [
        // User Management
        ['name' => 'manage_users', 'description' => 'Create, edit, delete users'],
        ['name' => 'view_users', 'description' => 'View users list'],
        
        // Role Management
        ['name' => 'manage_roles', 'description' => 'Create, edit, delete roles and permissions'],
        ['name' => 'view_roles', 'description' => 'View roles list'],
        
        // Blog Management
        ['name' => 'manage_blogs', 'description' => 'Create, edit, delete blogs'],
        ['name' => 'publish_blogs', 'description' => 'Publish/unpublish blogs'],
        ['name' => 'view_blogs', 'description' => 'View blogs list'],
        
        // Blog Category Management
        ['name' => 'manage_blog_categories', 'description' => 'Create, edit, delete blog categories'],
        
        // Service Management
        ['name' => 'manage_services', 'description' => 'Create, edit, delete services'],
        ['name' => 'view_services', 'description' => 'View services list'],
        
        // Team Management
        ['name' => 'manage_teams', 'description' => 'Create, edit, delete teams and employees'],
        ['name' => 'view_teams', 'description' => 'View teams list'],
        
        // Company Management
        ['name' => 'manage_company', 'description' => 'Edit company profile'],
    ];

    $permissionIds = [];
    foreach ($permissions as $permission) {
        $id = strtolower(\Ulid\Ulid::generate());
        $permissionIds[$permission['name']] = $id;
        
        Database::execute(
            "INSERT INTO permissions (id, name, description) VALUES (:id, :name, :description)",
            ['id' => $id, 'name' => $permission['name'], 'description' => $permission['description']]
        );
        echo "✓ Permission created: {$permission['name']}\n";
    }

    // Assign all permissions to Super Admin
    foreach ($permissionIds as $permissionId) {
        Database::execute(
            "INSERT INTO permission_role (role_id, permission_id) VALUES (:role_id, :permission_id)",
            ['role_id' => $superAdminRoleId, 'permission_id' => $permissionId]
        );
    }
    echo "✓ All permissions assigned to Super Admin\n";

    // Assign selected permissions to Admin
    $adminPermissions = [
        'view_users', 'manage_blogs', 'publish_blogs', 'view_blogs',
        'manage_blog_categories', 'manage_services', 'view_services',
        'manage_teams', 'view_teams', 'manage_company'
    ];
    foreach ($adminPermissions as $permName) {
        Database::execute(
            "INSERT INTO permission_role (role_id, permission_id) VALUES (:role_id, :permission_id)",
            ['role_id' => $adminRoleId, 'permission_id' => $permissionIds[$permName]]
        );
    }
    echo "✓ Selected permissions assigned to Admin\n";

    // Assign selected permissions to Editor
    $editorPermissions = [
        'manage_blogs', 'view_blogs', 'manage_blog_categories',
        'manage_services', 'view_services'
    ];
    foreach ($editorPermissions as $permName) {
        Database::execute(
            "INSERT INTO permission_role (role_id, permission_id) VALUES (:role_id, :permission_id)",
            ['role_id' => $editorRoleId, 'permission_id' => $permissionIds[$permName]]
        );
    }
    echo "✓ Selected permissions assigned to Editor\n";

    Database::commit();
    echo "\n Roles and permissions seeded successfully!\n";

} catch (Exception $e) {
    Database::rollback();
    echo "\n Error: " . $e->getMessage() . "\n";
    exit(1);
}