<?php

namespace Database\Factories;

use App\Modules\Category\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Category\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'slug' => fake()->slug(),
            'description' => fake()->paragraph(),
            'parent_id' => null,
            'order' => fake()->numberBetween(0, 100),
            'is_active' => true,
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->paragraph(),
            'meta_keywords' => fake()->words(5, true),
        ];
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}
