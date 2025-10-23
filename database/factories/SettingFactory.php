<?php

namespace Database\Factories;

use App\Modules\Setting\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(2),
            'value' => fake()->word(),
            'group' => fake()->randomElement(['general', 'email', 'social', 'seo']),
            'type' => fake()->randomElement(['text', 'boolean', 'integer', 'json']),
            'description' => fake()->sentence(),
            'is_public' => fake()->boolean(),
        ];
    }

    public function group(string $group): static
    {
        return $this->state(fn (array $attributes) => [
            'group' => $group,
        ]);
    }

    public function type(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }
}
