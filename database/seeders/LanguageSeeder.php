<?php

namespace Database\Seeders;

use App\Modules\Language\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'code' => 'id',
                'name' => 'Indonesian',
                'native_name' => 'Bahasa Indonesia',
                'flag' => '🇮🇩',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => true,
                'order' => 1,
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'flag' => '🇬🇧',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'order' => 2,
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }

        $this->command->info('✅ Languages seeded successfully!');
    }
}
