<?php

namespace Database\Factories;

use App\Modules\Menu\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        
        return [
            'name' => $name,
            'display_name' => ucwords($name),
            'location' => fake()->randomElement(['header', 'footer', 'sidebar', 'main']),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
