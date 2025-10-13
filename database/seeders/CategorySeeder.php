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
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Latest news and updates about technology, gadgets, and innovation.',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business insights, entrepreneurship, and market trends.',
                'color' => '#10B981',
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description' => 'Health, wellness, travel, and life improvement tips.',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'UI/UX design, graphic design, and creative inspiration.',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Coding tutorials, development tips, and best practices.',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Digital marketing strategies, SEO, and social media tips.',
                'color' => '#EC4899',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
