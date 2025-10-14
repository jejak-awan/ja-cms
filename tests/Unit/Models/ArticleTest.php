<?php

use App\Modules\Article\Models\Article;
use App\Modules\User\Models\User;
use App\Modules\Category\Models\Category;
use App\Modules\Tag\Models\Tag;

describe('Article Model', function () {
    
    test('has correct fillable attributes', function () {
        $article = new Article();
        
        $fillable = $article->getFillable();
        
        expect($fillable)->toContain('title', 'slug', 'content', 'excerpt', 'status', 'featured');
    });
    
    test('belongs to user', function () {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);
        
        expect($article->user)->toBeInstanceOf(User::class);
        expect($article->user->id)->toBe($user->id);
    });
    
    test('can belong to a category', function () {
        $category = Category::factory()->create();
        $article = Article::factory()->create(['category_id' => $category->id]);
        
        expect($article->category)->toBeInstanceOf(Category::class);
        expect($article->category->id)->toBe($category->id);
    });
    
    test('can have multiple tags', function () {
        $article = Article::factory()->create();
        $tags = Tag::factory()->count(3)->create();
        
        $article->tags()->attach($tags->pluck('id'));
        
        expect($article->tags)->toHaveCount(3);
        expect($article->tags->first())->toBeInstanceOf(Tag::class);
    });
    
    test('published scope returns only published articles', function () {
        Article::factory()->count(5)->create(['status' => 'published', 'published_at' => now()]);
        Article::factory()->count(3)->create(['status' => 'draft']);
        
        $published = Article::published()->get();
        
        expect($published->count())->toBeGreaterThanOrEqual(5);
        expect($published->every(fn($article) => $article->status === 'published'))->toBeTrue();
    });
    
    test('draft scope returns only draft articles', function () {
        Article::factory()->count(3)->create(['status' => 'draft']);
        Article::factory()->count(5)->create(['status' => 'published', 'published_at' => now()]);
        
        $drafts = Article::draft()->get();
        
        expect($drafts->count())->toBeGreaterThanOrEqual(3);
        expect($drafts->every(fn($article) => $article->status === 'draft'))->toBeTrue();
    });
    
    test('featured scope returns only featured articles', function () {
        Article::factory()->count(2)->create(['featured' => true, 'status' => 'published', 'published_at' => now()]);
        Article::factory()->count(3)->create(['featured' => false]);
        
        $featured = Article::featured()->get();
        
        expect($featured->count())->toBeGreaterThanOrEqual(2);
        expect($featured->every(fn($article) => $article->featured === true))->toBeTrue();
    });
    
    test('article can be published', function () {
        $article = Article::factory()->create(['status' => 'draft', 'published_at' => null]);
        
        $article->publish();
        
        expect($article->fresh()->status)->toBe('published');
        expect($article->fresh()->published_at)->not->toBeNull();
    });
    
    test('article can be unpublished', function () {
        $article = Article::factory()->create(['status' => 'published', 'published_at' => now()]);
        
        $article->unpublish();
        
        expect($article->fresh()->status)->toBe('draft');
    });
    
    test('article can be archived', function () {
        $article = Article::factory()->create(['status' => 'published']);
        
        $article->archive();
        
        expect($article->fresh()->status)->toBe('archived');
    });
    
    test('article can be featured', function () {
        $article = Article::factory()->create(['featured' => false]);
        
        $article->feature();
        
        expect($article->fresh()->featured)->toBeTrue();
    });
    
    test('article can increment views', function () {
        $article = Article::factory()->create(['views' => 10]);
        
        $article->incrementViews();
        
        expect($article->fresh()->views)->toBe(11);
    });
    
    test('is_published accessor returns correct boolean', function () {
        $published = Article::factory()->create(['status' => 'published']);
        $draft = Article::factory()->create(['status' => 'draft']);
        
        expect($published->is_published)->toBeTrue();
        expect($draft->is_published)->toBeFalse();
    });
    
    test('url accessor returns correct route', function () {
        $article = Article::factory()->create(['slug' => 'test-article']);
        
        expect($article->url)->toContain('test-article');
    });
    
    test('search scope finds articles by title', function () {
        Article::factory()->create(['title' => 'Laravel Tutorial', 'status' => 'published', 'published_at' => now()]);
        Article::factory()->create(['title' => 'PHP Best Practices', 'status' => 'published', 'published_at' => now()]);
        Article::factory()->create(['title' => 'Laravel Tips and Tricks', 'status' => 'published', 'published_at' => now()]);
        
        $results = Article::search('Laravel')->get();
        
        expect($results->count())->toBeGreaterThanOrEqual(2);
    });
    
    test('recent scope orders by published_at descending', function () {
        $oldest = Article::factory()->published()->create(['published_at' => now()->subDays(3)]);
        $newest = Article::factory()->published()->create(['published_at' => now()]);
        $middle = Article::factory()->published()->create(['published_at' => now()->subDays(1)]);
        
        $recent = Article::recent()->limit(3)->get();
        
        expect($recent->first()->id)->toBe($newest->id);
    });
    
    test('popular scope orders by views descending', function () {
        $low = Article::factory()->create(['views' => 10]);
        $high = Article::factory()->create(['views' => 100]);
        $medium = Article::factory()->create(['views' => 50]);
        
        $popular = Article::popular()->limit(3)->get();
        
        expect($popular->first()->id)->toBe($high->id);
    });
});
