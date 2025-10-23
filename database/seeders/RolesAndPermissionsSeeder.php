<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
            ],
            [
                'name' => 'editor',
                'display_name' => 'Editor',
                'description' => 'Can create, edit, and publish all content',
            ],
            [
                'name' => 'author',
                'display_name' => 'Author',
                'description' => 'Can create and edit own content',
            ],
            [
                'name' => 'subscriber',
                'display_name' => 'Subscriber',
                'description' => 'Can read content and manage own profile',
            ],
        ];

        foreach ($roles as $roleData) {
            \App\Modules\User\Models\Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $permissions = [
            // Articles
            ['name' => 'articles.view', 'display_name' => 'View Articles', 'group' => 'articles'],
            ['name' => 'articles.create', 'display_name' => 'Create Articles', 'group' => 'articles'],
            ['name' => 'articles.edit', 'display_name' => 'Edit Articles', 'group' => 'articles'],
            ['name' => 'articles.delete', 'display_name' => 'Delete Articles', 'group' => 'articles'],
            ['name' => 'articles.publish', 'display_name' => 'Publish Articles', 'group' => 'articles'],

            // Pages
            ['name' => 'pages.view', 'display_name' => 'View Pages', 'group' => 'pages'],
            ['name' => 'pages.create', 'display_name' => 'Create Pages', 'group' => 'pages'],
            ['name' => 'pages.edit', 'display_name' => 'Edit Pages', 'group' => 'pages'],
            ['name' => 'pages.delete', 'display_name' => 'Delete Pages', 'group' => 'pages'],
            ['name' => 'pages.publish', 'display_name' => 'Publish Pages', 'group' => 'pages'],

            // Categories
            ['name' => 'categories.view', 'display_name' => 'View Categories', 'group' => 'categories'],
            ['name' => 'categories.create', 'display_name' => 'Create Categories', 'group' => 'categories'],
            ['name' => 'categories.edit', 'display_name' => 'Edit Categories', 'group' => 'categories'],
            ['name' => 'categories.delete', 'display_name' => 'Delete Categories', 'group' => 'categories'],

            // Media
            ['name' => 'media.view', 'display_name' => 'View Media', 'group' => 'media'],
            ['name' => 'media.upload', 'display_name' => 'Upload Media', 'group' => 'media'],
            ['name' => 'media.edit', 'display_name' => 'Edit Media', 'group' => 'media'],
            ['name' => 'media.delete', 'display_name' => 'Delete Media', 'group' => 'media'],

            // Users
            ['name' => 'users.view', 'display_name' => 'View Users', 'group' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'group' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'group' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'group' => 'users'],

            // Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'group' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'group' => 'settings'],
        ];

        foreach ($permissions as $permissionData) {
            \App\Modules\User\Models\Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        // Assign permissions to roles
        $admin = \App\Modules\User\Models\Role::where('name', 'admin')->first();
        $editor = \App\Modules\User\Models\Role::where('name', 'editor')->first();
        $author = \App\Modules\User\Models\Role::where('name', 'author')->first();
        $subscriber = \App\Modules\User\Models\Role::where('name', 'subscriber')->first();

        // Admin gets all permissions (handled by User model)
        
        // Editor permissions
        $editor->syncPermissions([
            'articles.view', 'articles.create', 'articles.edit', 'articles.delete', 'articles.publish',
            'pages.view', 'pages.create', 'pages.edit', 'pages.delete', 'pages.publish',
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            'media.view', 'media.upload', 'media.edit', 'media.delete',
        ]);

        // Author permissions
        $author->syncPermissions([
            'articles.view', 'articles.create', 'articles.edit',
            'pages.view',
            'categories.view',
            'media.view', 'media.upload', 'media.edit',
        ]);

        // Subscriber permissions
        $subscriber->syncPermissions([
            'articles.view',
            'pages.view',
            'categories.view',
            'media.view',
        ]);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
