<?php

namespace Database\Factories;

use App\Modules\Page\Models\Page;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Page\Models\Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->slug(),
            'content' => fake()->paragraphs(5, true),
            'excerpt' => fake()->paragraph(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'template' => 'default',
            'status' => 'draft',
            'order' => fake()->numberBetween(0, 100),
            'views' => fake()->numberBetween(0, 500),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->paragraph(),
            'meta_keywords' => fake()->words(5, true),
        ];
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
                'published_at' => now(),
            ];
        });
    }
}
