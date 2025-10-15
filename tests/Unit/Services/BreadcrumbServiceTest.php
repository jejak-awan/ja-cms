<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\BreadcrumbService;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Page\Models\Page;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BreadcrumbServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_generates_breadcrumbs_for_article()
    {
        $category = Category::factory()->create([
            'name_id' => 'Technology',
            'description_id' => 'Tech category'
        ]);

        $article = Article::factory()->create([
            'title_id' => 'Laravel Tutorial',
            'content_id' => 'Content here',
            'category_id' => $category->id,
            'user_id' => $this->user->id
        ]);

        $breadcrumb = new BreadcrumbService();
        $breadcrumb->addHome('Home', '/')
            ->add('Technology', '/categories/technology')
            ->addCurrent('Laravel Tutorial');

        $breadcrumbs = $breadcrumb->get();

        $this->assertCount(3, $breadcrumbs); // Home + Category + Article
        $this->assertEquals('Home', $breadcrumbs[0]['title']);
        $this->assertEquals('Technology', $breadcrumbs[1]['title']);
        $this->assertEquals('Laravel Tutorial', $breadcrumbs[2]['title']);
        $this->assertTrue($breadcrumbs[2]['active']);
    }

    /** @test */
    public function it_generates_breadcrumbs_for_category_with_parent()
    {
        $parentCategory = Category::factory()->create([
            'name_id' => 'Technology',
            'description_id' => 'Tech category'
        ]);

        $childCategory = Category::factory()->create([
            'name_id' => 'Programming',
            'description_id' => 'Programming category',
            'parent_id' => $parentCategory->id
        ]);

        $breadcrumb = new BreadcrumbService();
        $breadcrumb->addHome('Home', '/')
            ->add('Technology', '/categories/technology')
            ->addCurrent('Programming');

        $breadcrumbs = $breadcrumb->get();

        $this->assertCount(3, $breadcrumbs); // Home + Parent + Child
        $this->assertEquals('Home', $breadcrumbs[0]['title']);
        $this->assertEquals('Technology', $breadcrumbs[1]['title']);
        $this->assertEquals('Programming', $breadcrumbs[2]['title']);
        $this->assertTrue($breadcrumbs[2]['active']);
    }

    /** @test */
    public function it_generates_breadcrumbs_for_page_with_parent()
    {
        $parentPage = Page::factory()->create([
            'title_id' => 'About Us',
            'content_id' => 'About content',
            'user_id' => $this->user->id
        ]);

        $childPage = Page::factory()->create([
            'title_id' => 'Our Team',
            'content_id' => 'Team content',
            'parent_id' => $parentPage->id,
            'user_id' => $this->user->id
        ]);

        $breadcrumb = new BreadcrumbService();
        $breadcrumb->addHome('Home', '/')
            ->add('About Us', '/about')
            ->addCurrent('Our Team');

        $breadcrumbs = $breadcrumb->get();

        $this->assertCount(3, $breadcrumbs); // Home + Parent + Child
        $this->assertEquals('Home', $breadcrumbs[0]['title']);
        $this->assertEquals('About Us', $breadcrumbs[1]['title']);
        $this->assertEquals('Our Team', $breadcrumbs[2]['title']);
        $this->assertTrue($breadcrumbs[2]['active']);
    }

    /** @test */
    public function it_includes_home_link()
    {
        $breadcrumb = new BreadcrumbService();
        $breadcrumb->addHome('Home', '/')->addCurrent('Test Page');

        $items = $breadcrumb->get();

        $this->assertCount(2, $items);
        $this->assertEquals('Home', $items[0]['title']);
        $this->assertFalse($items[0]['active']);
        $this->assertEquals('Test Page', $items[1]['title']);
        $this->assertTrue($items[1]['active']);
    }

    /** @test */
    public function it_handles_multiple_levels()
    {
        $breadcrumb = new BreadcrumbService();
        $breadcrumb->addHome('Home', '/')
            ->add('Category 1', '/cat1')
            ->add('Category 2', '/cat1/cat2')
            ->add('Category 3', '/cat1/cat2/cat3')
            ->addCurrent('Final Page');

        $items = $breadcrumb->get();

        $this->assertCount(5, $items);
        $this->assertEquals('Home', $items[0]['title']);
        $this->assertEquals('Category 1', $items[1]['title']);
        $this->assertEquals('Category 2', $items[2]['title']);
        $this->assertEquals('Category 3', $items[3]['title']);
        $this->assertEquals('Final Page', $items[4]['title']);
        $this->assertTrue($items[4]['active']);
    }

    /** @test */
    public function it_returns_correct_structure()
    {
        $breadcrumb = new BreadcrumbService();
        $breadcrumb->add('Test', '/test')->addCurrent('Current');

        $items = $breadcrumb->get();

        $this->assertIsArray($items);
        $this->assertArrayHasKey('title', $items[0]);
        $this->assertArrayHasKey('url', $items[0]);
        $this->assertArrayHasKey('active', $items[0]);
        $this->assertEquals('Test', $items[0]['title']);
        $this->assertEquals('/test', $items[0]['url']);
        $this->assertFalse($items[0]['active']);
    }

    /** @test */
    public function it_renders_html_correctly()
    {
        $breadcrumb = new BreadcrumbService();
        $breadcrumb->addHome('Home', '/')->add('Test', '/test')->addCurrent('Current');

        $html = $breadcrumb->render();

        $this->assertStringContainsString('<nav class="breadcrumb"', $html);
        $this->assertStringContainsString('<ol>', $html);
        $this->assertStringContainsString('<li>', $html);
        $this->assertStringContainsString('<a href="', $html);
        $this->assertStringContainsString('›', $html);
    }

    /** @test */
    public function it_handles_missing_models_gracefully()
    {
        $breadcrumb = new BreadcrumbService();
        $breadcrumb->addHome('Home', '/')->addCurrent('Test');

        $this->assertFalse($breadcrumb->isEmpty());
        $this->assertEquals(2, $breadcrumb->count());
    }

    /** @test */
    public function it_generates_search_breadcrumbs()
    {
        $breadcrumb = new BreadcrumbService();
        $breadcrumb->addHome('Home', '/')->addCurrent('Search: laravel tutorial');

        $breadcrumbs = $breadcrumb->get();

        $this->assertCount(2, $breadcrumbs);
        $this->assertEquals('Home', $breadcrumbs[0]['title']);
        $this->assertEquals('Search: laravel tutorial', $breadcrumbs[1]['title']);
        $this->assertTrue($breadcrumbs[1]['active']);
    }

    /** @test */
    public function it_renders_json_ld_correctly()
    {
        $breadcrumb = new BreadcrumbService();
        $breadcrumb->addHome('Home', '/')->add('Test', '/test')->addCurrent('Current');

        $json = $breadcrumb->renderJson();
        $data = json_decode($json, true);
        
        $this->assertArrayHasKey('@context', $data);
        $this->assertArrayHasKey('@type', $data);
        $this->assertArrayHasKey('itemListElement', $data);
        $this->assertEquals('https://schema.org', $data['@context']);
        $this->assertEquals('BreadcrumbList', $data['@type']);
        $this->assertCount(3, $data['itemListElement']);
    }
}