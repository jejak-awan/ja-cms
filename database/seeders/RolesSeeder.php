<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\User\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissions = Role::getDefaultPermissions();
        
        $roles = [
            [
                'name' => 'Administrator',
                'display_name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full access to all features',
                'permissions' => $allPermissions
            ],
            [
                'name' => 'Editor',
                'display_name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Can manage content but not system settings',
                'permissions' => [
                    'view_dashboard',
                    'manage_articles',
                    'manage_categories',
                    'manage_pages',
                    'manage_media',
                    'manage_menus',
                ]
            ],
            [
                'name' => 'Author',
                'display_name' => 'Author',
                'slug' => 'author',
                'description' => 'Can create and edit own articles',
                'permissions' => [
                    'view_dashboard',
                    'manage_articles',
                    'manage_media',
                ]
            ],
            [
                'name' => 'Subscriber',
                'display_name' => 'Subscriber',
                'slug' => 'subscriber',
                'description' => 'Read-only access',
                'permissions' => [
                    'view_dashboard',
                ]
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
