<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Header Menu
        $headerMenu = \App\Modules\Menu\Models\Menu::create([
            'name' => 'Header Menu',
            'location' => 'header',
            'is_active' => true,
        ]);

        // Header menu items
        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Home',
            'route' => 'home',
            'type' => 'custom',
            'order' => 0,
            'is_active' => true,
        ]);

        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Articles',
            'route' => 'articles.index',
            'type' => 'custom',
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Categories',
            'route' => 'categories.index',
            'type' => 'custom',
            'order' => 2,
            'is_active' => true,
        ]);

        // Create Footer Menu
        $footerMenu = \App\Modules\Menu\Models\Menu::create([
            'name' => 'Footer Menu',
            'location' => 'footer',
            'is_active' => true,
        ]);

        // Footer menu items
        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'About Us',
            'url' => '/about',
            'type' => 'custom',
            'order' => 0,
            'is_active' => true,
        ]);

        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Contact',
            'url' => '/contact',
            'type' => 'custom',
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Privacy Policy',
            'url' => '/privacy',
            'type' => 'custom',
            'order' => 2,
            'is_active' => true,
        ]);

        // Create Social Menu
        $socialMenu = \App\Modules\Menu\Models\Menu::create([
            'name' => 'Social Links',
            'location' => 'social',
            'is_active' => true,
        ]);

        // Social menu items
        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $socialMenu->id,
            'title' => 'Facebook',
            'url' => 'https://facebook.com/yourpage',
            'type' => 'custom',
            'target' => '_blank',
            'icon' => 'facebook',
            'order' => 0,
            'is_active' => true,
        ]);

        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $socialMenu->id,
            'title' => 'Twitter',
            'url' => 'https://twitter.com/yourhandle',
            'type' => 'custom',
            'target' => '_blank',
            'icon' => 'twitter',
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $socialMenu->id,
            'title' => 'Instagram',
            'url' => 'https://instagram.com/yourhandle',
            'type' => 'custom',
            'target' => '_blank',
            'icon' => 'instagram',
            'order' => 2,
            'is_active' => true,
        ]);

        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $socialMenu->id,
            'title' => 'LinkedIn',
            'url' => 'https://linkedin.com/company/yourcompany',
            'type' => 'custom',
            'target' => '_blank',
            'icon' => 'linkedin',
            'order' => 3,
            'is_active' => true,
        ]);

        \App\Modules\Menu\Models\MenuItem::create([
            'menu_id' => $socialMenu->id,
            'title' => 'YouTube',
            'url' => 'https://youtube.com/@yourchannel',
            'type' => 'custom',
            'target' => '_blank',
            'icon' => 'youtube',
            'order' => 4,
            'is_active' => true,
        ]);

        $this->command->info('Menus seeded successfully!');
    }
}
