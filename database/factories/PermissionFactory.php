<?php

namespace Database\Factories;

use App\Modules\User\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\User\Models\Permission>
 */
class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $action = fake()->randomElement(['view', 'create', 'edit', 'delete', 'publish']);
        $resource = fake()->randomElement(['articles', 'pages', 'categories', 'media', 'users']);
        $unique = fake()->unique()->numberBetween(1, 99999);
        
        return [
            'name' => ucfirst($action) . ' ' . ucfirst($resource) . ' ' . $unique,
            'slug' => $resource . '.' . $action . '.' . $unique,
            'display_name' => ucfirst($action) . ' ' . ucfirst($resource),
            'description' => fake()->sentence(),
            'group' => ucfirst($resource),
        ];
    }
}
