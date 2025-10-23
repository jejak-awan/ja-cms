<?php

namespace Database\Factories;

use App\Modules\Language\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Language\Models\Language>
 */
class LanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Language::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->languageCode(),
            'name' => fake()->country(),
            'native_name' => fake()->country(),
            'flag' => '🌐',
            'direction' => 'ltr',
            'is_active' => true,
            'is_default' => false,
            'order' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the language is the default.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
            'order' => 1,
        ]);
    }

    /**
     * Indicate that the language is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indonesian language.
     */
    public function indonesian(): static
    {
        return $this->state(fn (array $attributes) => [
            'code' => 'id',
            'name' => 'Indonesian',
            'native_name' => 'Bahasa Indonesia',
            'flag' => '🇮🇩',
            'direction' => 'ltr',
            'is_active' => true,
            'is_default' => true,
            'order' => 1,
        ]);
    }

    /**
     * English language.
     */
    public function english(): static
    {
        return $this->state(fn (array $attributes) => [
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag' => '🇬🇧',
            'direction' => 'ltr',
            'is_active' => true,
            'is_default' => false,
            'order' => 2,
        ]);
    }

    /**
     * RTL language (e.g., Arabic).
     */
    public function rtl(): static
    {
        return $this->state(fn (array $attributes) => [
            'direction' => 'rtl',
        ]);
    }
}
