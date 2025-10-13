<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Menu\Models\Menu;
use App\Modules\Menu\Models\MenuItem;

class MenuItemsSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::where('name', 'main-menu')->first();
        
        if (!$menu) {
            $menu = Menu::create([
                'name' => 'main-menu',
                'display_name' => 'Main Navigation',
                'location' => 'header',
                'is_active' => true
            ]);
        }

        // Delete existing items
        MenuItem::where('menu_id', $menu->id)->delete();

        // Create test menu items
        $items = [
            ['title' => 'Home', 'url' => '/', 'type' => 'custom', 'order' => 0],
            ['title' => 'About', 'url' => '/about', 'type' => 'custom', 'order' => 1],
            ['title' => 'Services', 'url' => '/services', 'type' => 'custom', 'order' => 2],
            ['title' => 'Blog', 'url' => '/blog', 'type' => 'custom', 'order' => 3],
            ['title' => 'Contact', 'url' => '/contact', 'type' => 'custom', 'order' => 4],
        ];

        foreach ($items as $data) {
            MenuItem::create(array_merge($data, [
                'menu_id' => $menu->id,
                'target' => '_self',
                'is_active' => true
            ]));
        }

        $this->command->info('Created ' . count($items) . ' menu items');
    }
}
