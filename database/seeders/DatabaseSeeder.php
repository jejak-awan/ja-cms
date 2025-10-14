<?php

namespace Database\Seeders;

use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
        ]);

        // Seed categories and articles
        $this->call([
            LanguageSeeder::class,
            CategorySeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
