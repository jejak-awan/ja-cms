<?php

namespace Database\Factories;

use App\Modules\User\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\User\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();
        
        return [
            'name' => ucfirst($name),
            'slug' => strtolower($name),
            'display_name' => ucfirst($name),
            'description' => fake()->sentence(),
            'permissions' => [],
        ];
    }
}
