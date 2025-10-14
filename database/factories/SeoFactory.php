<?php

namespace Database\Factories;

use App\Modules\Seo\Models\Seo;
use App\Modules\Article\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoFactory extends Factory
{
    protected $model = Seo::class;

    public function definition(): array
    {
        return [
            'seoable_type' => Article::class,
            'seoable_id' => Article::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'keywords' => fake()->words(5, true),
            'og_title' => fake()->sentence(),
            'og_description' => fake()->paragraph(),
            'og_image' => fake()->imageUrl(),
            'og_type' => 'article',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => fake()->sentence(),
            'twitter_description' => fake()->paragraph(),
            'twitter_image' => fake()->imageUrl(),
            'canonical_url' => fake()->url(),
            'no_index' => false,
            'no_follow' => false,
        ];
    }

    public function forModel($model): static
    {
        return $this->state(fn (array $attributes) => [
            'seoable_type' => get_class($model),
            'seoable_id' => $model->id,
        ]);
    }

    public function noIndex(): static
    {
        return $this->state(fn (array $attributes) => [
            'no_index' => true,
        ]);
    }

    public function noFollow(): static
    {
        return $this->state(fn (array $attributes) => [
            'no_follow' => true,
        ]);
    }
}
