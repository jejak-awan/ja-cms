<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ArticleSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin dashboard access
     */
    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test admin dashboard loads with data
     */
    public function test_admin_dashboard_loads_successfully_with_auth(): void
    {
        // Create admin user
        $admin = \App\Modules\User\Models\User::factory()->create([
            'role' => 'admin',
            'is_active' => true
        ]);

        // Create some test data
        \App\Modules\Category\Models\Category::factory()->count(3)->create();
        \App\Modules\Article\Models\Article::factory()->count(5)->create();
        \App\Modules\Page\Models\Page::factory()->count(2)->create();

        $response = $this->actingAs($admin)->get('/admin');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas(['stats', 'chartData', 'activities']);
    }

    /**
     * Test activity feed API endpoint
     */
    public function test_activity_feed_api_returns_json(): void
    {
        $admin = \App\Modules\User\Models\User::factory()->create([
            'role' => 'admin',
            'is_active' => true
        ]);

        // Create test data
        \App\Modules\Article\Models\Article::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/admin/activity-feed');
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $response->assertJsonStructure([
            'success',
            'data',
            'page',
            'per_page',
            'type'
        ]);
    }

    /**
     * Test activity feed filtering
     */
    public function test_activity_feed_can_filter_by_type(): void
    {
        $admin = \App\Modules\User\Models\User::factory()->create([
            'role' => 'admin',
            'is_active' => true
        ]);

        // Create test data
        \App\Modules\Article\Models\Article::factory()->count(2)->create();
        \App\Modules\User\Models\User::factory()->count(1)->create();

        $response = $this->actingAs($admin)->get('/admin/activity-feed?type=article');
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'type' => 'article'
        ]);
    }
}