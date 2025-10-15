<?php

namespace Database\Factories;

use App\Modules\Article\Models\Article;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Article\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_id' => fake()->sentence(),
            'title_en' => fake()->sentence(),
            'slug' => fake()->slug(),
            'excerpt_id' => fake()->paragraph(),
            'excerpt_en' => fake()->paragraph(),
            'content_id' => fake()->paragraphs(5, true),
            'content_en' => fake()->paragraphs(5, true),
            'category_id' => \App\Modules\Category\Models\Category::factory(),
            'user_id' => User::factory(),
            'featured_image' => null,
            'status' => 'draft',
            'published_at' => null,
            'featured' => false,
            'views' => fake()->numberBetween(0, 1000),
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

    public function featured()
    {
        return $this->state(function (array $attributes) {
            return [
                'featured' => true,
            ];
        });
    }
}
