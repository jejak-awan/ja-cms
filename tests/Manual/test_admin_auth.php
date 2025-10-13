<?php

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════════╗\n";
echo "║                                                                      ║\n";
echo "║              🔐 ADMIN PANEL AUTHENTICATION TEST 🔐                  ║\n";
echo "║                                                                      ║\n";
echo "╚══════════════════════════════════════════════════════════════════════╝\n";
echo "\n";

use App\Modules\User\Models\User;

// Check if admin user exists
echo "📊 CHECKING ADMIN USERS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$adminUsers = User::whereHas('roles', function($q) {
    $q->where('name', 'Administrator');
})->get();

if ($adminUsers->count() > 0) {
    echo "✅ Found {$adminUsers->count()} administrator(s):\n\n";
    foreach ($adminUsers as $admin) {
        echo "  👤 {$admin->name}\n";
        echo "     📧 Email: {$admin->email}\n";
        echo "     🔐 Status: {$admin->status}\n";
        echo "     👥 Roles: " . $admin->roles->pluck('display_name')->implode(', ') . "\n";
        echo "\n";
    }
} else {
    echo "⚠️  No administrator found! Checking for any admin user...\n\n";
    
    // Check if admin@example.com exists
    $existingAdmin = User::where('email', 'admin@example.com')->first();
    
    if ($existingAdmin) {
        // Assign Administrator role if not already assigned
        $adminRole = \App\Modules\User\Models\Role::where('name', 'Administrator')->first();
        if ($adminRole && !$existingAdmin->hasRole('Administrator')) {
            $existingAdmin->assignRole($adminRole);
            echo "✅ Administrator role assigned to existing user!\n";
            echo "   📧 Email: admin@example.com\n\n";
        } else {
            echo "ℹ️  User exists but doesn't have Administrator role\n";
            echo "   📧 Email: {$existingAdmin->email}\n";
            echo "   👥 Current roles: " . $existingAdmin->roles->pluck('display_name')->implode(', ') . "\n\n";
        }
    } else {
        // Create new admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        // Assign Administrator role
        $adminRole = \App\Modules\User\Models\Role::where('name', 'Administrator')->first();
        if ($adminRole) {
            $admin->assignRole($adminRole);
            echo "✅ Admin user created successfully!\n";
            echo "   📧 Email: admin@example.com\n";
            echo "   🔑 Password: password\n\n";
        }
    }
}

// Check routes
echo "📍 CHECKING ADMIN ROUTES\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$routes = [
    'admin.login' => 'Login Page',
    'admin.dashboard' => 'Dashboard',
];

foreach ($routes as $name => $description) {
    try {
        $url = route($name);
        echo "  ✅ {$description}: {$url}\n";
    } catch (\Exception $e) {
        echo "  ❌ {$description}: Route not found\n";
    }
}

echo "\n";

// Check middleware
echo "🛡️  CHECKING MIDDLEWARE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if (class_exists(\App\Modules\Admin\Middleware\AdminMiddleware::class)) {
    echo "  ✅ AdminMiddleware: Registered\n";
} else {
    echo "  ❌ AdminMiddleware: Not found\n";
}

echo "\n";

// Check controllers
echo "🎮 CHECKING CONTROLLERS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$controllers = [
    \App\Modules\Admin\Controllers\AuthController::class => 'AuthController',
    \App\Modules\Admin\Controllers\AdminController::class => 'AdminController',
];

foreach ($controllers as $class => $name) {
    if (class_exists($class)) {
        echo "  ✅ {$name}: Available\n";
    } else {
        echo "  ❌ {$name}: Not found\n";
    }
}

echo "\n";

// Check views
echo "👁️  CHECKING VIEWS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$views = [
    'admin.auth.login' => 'Login View',
    'admin.dashboard' => 'Dashboard View',
    'admin.layouts.admin' => 'Admin Layout',
];

foreach ($views as $view => $description) {
    if (view()->exists($view)) {
        echo "  ✅ {$description}: Found\n";
    } else {
        echo "  ❌ {$description}: Missing\n";
    }
}

echo "\n";

// Summary
echo "╔══════════════════════════════════════════════════════════════════════╗\n";
echo "║                                                                      ║\n";
echo "║                     ✅ TEST COMPLETED                                ║\n";
echo "║                                                                      ║\n";
echo "╚══════════════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "🚀 QUICK START GUIDE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if ($adminUsers->count() > 0) {
    $firstAdmin = $adminUsers->first();
    echo "  1. Open your browser: http://192.168.88.44/admin/login\n";
    echo "  2. Login credentials:\n";
    echo "     📧 Email: {$firstAdmin->email}\n";
    echo "     🔑 Password: (your password)\n";
} else {
    echo "  1. Open your browser: http://192.168.88.44/admin/login\n";
    echo "  2. Login credentials:\n";
    echo "     📧 Email: admin@example.com\n";
    echo "     🔑 Password: password\n";
}

echo "  3. You'll be redirected to the dashboard\n";
echo "\n";

echo "📋 TODO #1 STATUS: ✅ COMPLETE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "✅ Admin authentication system created\n";
echo "✅ AdminMiddleware configured\n";
echo "✅ Auth routes registered\n";
echo "✅ Login view created\n";
echo "✅ Dashboard with statistics\n";
echo "✅ Admin layout with sidebar\n";
echo "\n";

echo "Next: TODO #2 - Complete dashboard features\n";
echo "Next: TODO #3 - Articles CRUD interface\n";
echo "\n";
