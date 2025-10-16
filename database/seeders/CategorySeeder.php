<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Category\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_id' => 'Technology',
                'name_en' => 'Technology',
                'slug' => 'technology',
                'description_id' => 'Latest news and updates about technology, gadgets, and innovation.',
                'description_en' => 'Latest news and updates about technology, gadgets, and innovation.',
                'color' => '#3B82F6',
            ],
            [
                'name_id' => 'Business',
                'name_en' => 'Business',
                'slug' => 'business',
                'description_id' => 'Business insights, entrepreneurship, and market trends.',
                'description_en' => 'Business insights, entrepreneurship, and market trends.',
                'color' => '#10B981',
            ],
            [
                'name_id' => 'Lifestyle',
                'name_en' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description_id' => 'Health, wellness, travel, and life improvement tips.',
                'description_en' => 'Health, wellness, travel, and life improvement tips.',
                'color' => '#F59E0B',
            ],
            [
                'name_id' => 'Design',
                'name_en' => 'Design',
                'slug' => 'design',
                'description_id' => 'UI/UX design, graphic design, and creative inspiration.',
                'description_en' => 'UI/UX design, graphic design, and creative inspiration.',
                'color' => '#8B5CF6',
            ],
            [
                'name_id' => 'Programming',
                'name_en' => 'Programming',
                'slug' => 'programming',
                'description_id' => 'Coding tutorials, development tips, and best practices.',
                'description_en' => 'Coding tutorials, development tips, and best practices.',
                'color' => '#EF4444',
            ],
            [
                'name_id' => 'Marketing',
                'name_en' => 'Marketing',
                'slug' => 'marketing',
                'description_id' => 'Digital marketing strategies, SEO, and social media tips.',
                'description_en' => 'Digital marketing strategies, SEO, and social media tips.',
                'color' => '#EC4899',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
