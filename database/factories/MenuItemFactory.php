<?php

namespace Database\Factories;

use App\Modules\Menu\Models\MenuItem;
use App\Modules\Menu\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'parent_id' => null,
            'title' => fake()->words(3, true),
            'url' => fake()->url(),
            'route' => null,
            'type' => 'custom',
            'target_id' => null,
            'target' => '_self',
            'icon' => fake()->randomElement(['home', 'info', 'contact', null]),
            'css_class' => null,
            'order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }

    public function forMenu(Menu $menu): static
    {
        return $this->state(fn (array $attributes) => [
            'menu_id' => $menu->id,
        ]);
    }

    public function withParent(MenuItem $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'menu_id' => $parent->menu_id,
            'parent_id' => $parent->id,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
