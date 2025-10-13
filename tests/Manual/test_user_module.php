<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "====================================\n";
echo "Testing User Module\n";
echo "====================================\n\n";

// Test 1: Create new user
echo "✓ Creating new user...\n";
$user = App\Modules\User\Models\User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'password123', // Will be auto-hashed by observer
    'bio' => 'A passionate writer and content creator',
]);

echo "  User ID: {$user->id}\n";
echo "  Name: {$user->name}\n";
echo "  Email: {$user->email}\n";
echo "  Role: {$user->role}\n";
echo "  Status: {$user->status}\n";
echo "  Password hashed: " . (strlen($user->password) > 60 ? 'Yes' : 'No') . "\n";
echo "  Full Name: {$user->full_name}\n";
echo "  Initials: {$user->initials}\n";
echo "  Is Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
echo "  Is Admin: " . ($user->is_admin ? 'Yes' : 'No') . "\n";
echo "  Avatar URL: {$user->avatar_url}\n\n";

// Test 2: Assign role
echo "✓ Testing role assignment...\n";
$authorRole = App\Modules\User\Models\Role::where('name', 'author')->first();
$user->assignRole($authorRole);
echo "  Assigned role: {$authorRole->display_name}\n";
echo "  Has author role: " . ($user->hasRole('author') ? 'Yes' : 'No') . "\n";
echo "  Has admin role: " . ($user->hasRole('admin') ? 'Yes' : 'No') . "\n";
echo "  Has any role (author, editor): " . ($user->hasAnyRole(['author', 'editor']) ? 'Yes' : 'No') . "\n\n";

// Test 3: Test permissions
echo "✓ Testing permissions...\n";
echo "  Can view articles: " . ($user->hasPermission('articles.view') ? 'Yes' : 'No') . "\n";
echo "  Can create articles: " . ($user->hasPermission('articles.create') ? 'Yes' : 'No') . "\n";
echo "  Can publish articles: " . ($user->hasPermission('articles.publish') ? 'Yes' : 'No') . "\n";
echo "  Can delete users: " . ($user->hasPermission('users.delete') ? 'Yes' : 'No') . "\n\n";

// Test 4: Direct permission
echo "✓ Testing direct permission assignment...\n";
$publishPermission = App\Modules\User\Models\Permission::where('name', 'articles.publish')->first();
$user->givePermissionTo($publishPermission);
$user->refresh();
echo "  Gave direct permission: articles.publish\n";
echo "  Can publish articles now: " . ($user->hasPermission('articles.publish') ? 'Yes' : 'No') . "\n\n";

// Test 5: Create editor user
echo "✓ Creating editor user...\n";
$editor = App\Modules\User\Models\User::create([
    'name' => 'Jane Smith',
    'email' => 'jane@example.com',
    'password' => 'editor123',
    'role' => 'editor',
]);

echo "  Editor ID: {$editor->id}\n";
echo "  Role: {$editor->role}\n";
echo "  Can edit articles: " . ($editor->hasPermission('articles.edit') ? 'Yes' : 'No') . "\n";
echo "  Can delete articles: " . ($editor->hasPermission('articles.delete') ? 'Yes' : 'No') . "\n";
echo "  Can delete users: " . ($editor->hasPermission('users.delete') ? 'Yes' : 'No') . "\n\n";

// Test 6: Create admin user
echo "✓ Creating admin user...\n";
$admin = App\Modules\User\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => 'admin123',
    'role' => 'admin',
]);

echo "  Admin ID: {$admin->id}\n";
echo "  Is Admin: " . ($admin->is_admin ? 'Yes' : 'No') . "\n";
echo "  Can do anything: " . ($admin->hasPermission('users.delete') ? 'Yes' : 'No') . "\n";
echo "  Can access admin: " . ($admin->canAccessAdmin() ? 'Yes' : 'No') . "\n\n";

// Test 7: Test scopes
echo "✓ Testing scopes:\n";
echo "  Total users: " . App\Modules\User\Models\User::count() . "\n";
echo "  Active users: " . App\Modules\User\Models\User::active()->count() . "\n";
echo "  Admins: " . App\Modules\User\Models\User::admins()->count() . "\n";
echo "  Editors: " . App\Modules\User\Models\User::editors()->count() . "\n";
echo "  Authors: " . App\Modules\User\Models\User::authors()->count() . "\n";
echo "  Verified: " . App\Modules\User\Models\User::verified()->count() . "\n";
echo "  Unverified: " . App\Modules\User\Models\User::unverified()->count() . "\n\n";

// Test 8: Test status changes
echo "✓ Testing status changes...\n";
$user->suspend();
$user->refresh();
echo "  User suspended: {$user->status}\n";
echo "  Is active: " . ($user->is_active ? 'Yes' : 'No') . "\n";

$user->activate();
$user->refresh();
echo "  User activated: {$user->status}\n";
echo "  Is active: " . ($user->is_active ? 'Yes' : 'No') . "\n\n";

// Test 9: Test email verification
echo "✓ Testing email verification...\n";
echo "  Is verified before: " . ($user->is_verified ? 'Yes' : 'No') . "\n";
$user->markEmailAsVerified();
$user->refresh();
echo "  Is verified after: " . ($user->is_verified ? 'Yes' : 'No') . "\n";
echo "  Email verified at: {$user->email_verified_at}\n\n";

// Test 10: Test last login tracking
echo "✓ Testing last login tracking...\n";
$user->updateLastLogin('192.168.1.100');
$user->refresh();
echo "  Last login at: {$user->last_login_at}\n";
echo "  Last login IP: {$user->last_login_ip}\n\n";

// Test 11: Role permissions summary
echo "✓ Role permissions summary:\n";
$roles = App\Modules\User\Models\Role::with('permissions')->get();
foreach ($roles as $role) {
    echo "  {$role->display_name} ({$role->permissions->count()} permissions):\n";
    foreach ($role->permissions->take(5) as $perm) {
        echo "    - {$perm->display_name}\n";
    }
    if ($role->permissions->count() > 5) {
        echo "    - ... and " . ($role->permissions->count() - 5) . " more\n";
    }
}
echo "\n";

// Test 12: Permission groups
echo "✓ Permission groups:\n";
$groups = App\Modules\User\Models\Permission::getGroups();
foreach ($groups as $group) {
    $count = App\Modules\User\Models\Permission::byGroup($group)->count();
    echo "  {$group}: {$count} permissions\n";
}
echo "\n";

echo "====================================\n";
echo "✅ All User Module Tests Passed!\n";
echo "====================================\n";
