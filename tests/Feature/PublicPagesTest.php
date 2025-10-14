<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public homepage loads without database dependencies
     */
    public function test_homepage_loads_with_empty_database(): void
    {
        // Create minimal required data
        \App\Modules\Category\Models\Category::factory()->count(1)->create();
        
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }

    /**
     * Test public homepage with sample content
     */
    public function test_homepage_displays_articles(): void
    {
        // Create test data
        $category = \App\Modules\Category\Models\Category::factory()->create();
        \App\Modules\Article\Models\Article::factory()->count(3)->create([
            'category_id' => $category->id,
            'status' => 'published',
            'featured' => true
        ]);

        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertViewIs('public.pages.home');
    }
}