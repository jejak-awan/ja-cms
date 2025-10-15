<?php

namespace Tests\Unit\Observers;

use Tests\TestCase;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class ArticleObserverTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create([
            'name_id' => 'Test Category',
            'description_id' => 'Test description'
        ]);
    }

    /** @test */
    public function it_generates_slug_from_title_when_creating()
    {
        $article = Article::create([
            'title_id' => 'Test Article Title',
            'content_id' => 'Test content',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertEquals('test-article-title', $article->slug);
    }

    /** @test */
    public function it_generates_unique_slug_when_duplicate()
    {
        // Create first article
        Article::create([
            'title_id' => 'Test Article',
            'content_id' => 'Test content',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        // Create second article with same title
        $article2 = Article::create([
            'title_id' => 'Test Article',
            'content_id' => 'Test content',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertEquals('test-article-1', $article2->slug);
    }

    /** @test */
    public function it_auto_generates_excerpt_from_content()
    {
        $longContent = str_repeat('This is a long content. ', 15); // 375 characters
        
        $article = Article::create([
            'title_id' => 'Test Article',
            'content_id' => $longContent,
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertNotNull($article->excerpt_id);
        $this->assertLessThanOrEqual(203, strlen($article->excerpt_id)); // 200 + 3 for "..."
    }

    /** @test */
    public function it_preserves_manual_excerpt_if_provided()
    {
        $manualExcerpt = 'This is a manual excerpt';
        
        $article = Article::create([
            'title_id' => 'Test Article',
            'content_id' => 'Test content',
            'excerpt_id' => $manualExcerpt,
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertEquals($manualExcerpt, $article->excerpt_id);
    }

    /** @test */
    public function it_generates_meta_title_from_title_if_empty()
    {
        $article = Article::create([
            'title_id' => 'Test Article Title',
            'content_id' => 'Test content',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertEquals('Test Article Title', $article->meta_title);
    }

    /** @test */
    public function it_generates_meta_description_from_excerpt_if_empty()
    {
        $article = Article::create([
            'title_id' => 'Test Article',
            'content_id' => str_repeat('Test content. ', 20),
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertNotNull($article->meta_description);
        $this->assertLessThanOrEqual(200, strlen($article->meta_description));
    }

    /** @test */
    public function it_sets_published_at_when_status_changes_to_published()
    {
        $article = Article::create([
            'title_id' => 'Test Article',
            'content_id' => 'Test content',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertNull($article->published_at);

        $article->update(['status' => 'published']);

        $this->assertNotNull($article->fresh()->published_at);
    }

    /** @test */
    public function it_clears_cache_when_article_updated()
    {
        Cache::put('articles.cache', 'test data');
        
        $article = Article::create([
            'title_id' => 'Test Article',
            'content_id' => 'Test content',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $article->update(['title' => 'Updated Title']);

        $this->assertFalse(Cache::has('articles.cache'));
    }

    /** @test */
    public function it_clears_cache_when_article_deleted()
    {
        Cache::put('articles.cache', 'test data');
        
        $article = Article::create([
            'title_id' => 'Test Article',
            'content_id' => 'Test content',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $article->delete();

        $this->assertFalse(Cache::has('articles.cache'));
    }

    /** @test */
    public function it_updates_category_article_count()
    {
        $initialCount = $this->category->articles_count;
        
        Article::create([
            'title_id' => 'Test Article',
            'content_id' => 'Test content',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertEquals($initialCount + 1, $this->category->fresh()->articles_count);
    }
}