<?php

use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use Illuminate\Support\Str;

describe('ArticleObserver', function () {
    
    describe('creating event', function () {
        
        test('generates slug from title when creating', function () {
            $article = Article::factory()->make([
                'title' => 'This is a Test Article',
                'slug' => null,
            ]);
            
            $article->save();
            
            expect($article->slug)->toBe('this-is-a-test-article');
        });
        
        test('generates unique slug when duplicate exists', function () {
            // Create first article
            Article::factory()->create([
                'title' => 'Test Article',
                'slug' => 'test-article',
            ]);
            
            // Create second article with same title
            $article = Article::factory()->make([
                'title' => 'Test Article',
                'slug' => null,
            ]);
            
            $article->save();
            
            expect($article->slug)->toBe('test-article-1');
        });
        
        test('preserves manually provided slug', function () {
            $article = Article::factory()->make([
                'title' => 'Test Article',
                'slug' => 'custom-slug',
            ]);
            
            $article->save();
            
            expect($article->slug)->toBe('custom-slug');
        });
        
        test('auto-generates excerpt from content when not provided', function () {
            $content = str_repeat('This is a long content. ', 20);
            
            $article = Article::factory()->make([
                'content' => $content,
                'excerpt' => null,
            ]);
            
            $article->save();
            
            expect($article->excerpt)
                ->not->toBeNull()
                ->and(strlen($article->excerpt))->toBeLessThanOrEqual(203);
        });
        
        test('preserves manually provided excerpt', function () {
            $article = Article::factory()->make([
                'content' => 'Some long content here',
                'excerpt' => 'Custom excerpt',
            ]);
            
            $article->save();
            
            expect($article->excerpt)->toBe('Custom excerpt');
        });
        
        test('generates meta_title from title if empty', function () {
            $article = Article::factory()->make([
                'title' => 'Test Article Title',
                'meta_title' => null,
            ]);
            
            $article->save();
            
            expect($article->meta_title)->toBe('Test Article Title');
        });
        
        test('generates meta_description from excerpt if empty', function () {
            $article = Article::factory()->make([
                'excerpt' => 'This is a test excerpt',
                'meta_description' => null,
            ]);
            
            $article->save();
            
            expect($article->meta_description)->toContain('This is a test excerpt');
        });
        
        test('sets published_at when status is published and not set', function () {
            $article = Article::factory()->make([
                'status' => 'published',
                'published_at' => null,
            ]);
            
            $article->save();
            
            expect($article->published_at)->not->toBeNull();
        });
        
        test('does not set published_at when status is draft', function () {
            $article = Article::factory()->make([
                'status' => 'draft',
                'published_at' => null,
            ]);
            
            $article->save();
            
            expect($article->published_at)->toBeNull();
        });
        
        test('preserves existing published_at when provided', function () {
            $publishedAt = now()->subDays(5);
            
            $article = Article::factory()->make([
                'status' => 'published',
                'published_at' => $publishedAt,
            ]);
            
            $article->save();
            
            expect($article->published_at->format('Y-m-d'))->toBe($publishedAt->format('Y-m-d'));
        });
    });
    
    describe('updating event', function () {
        
        test('regenerates slug when title changes and slug was auto-generated', function () {
            $article = Article::factory()->create([
                'title' => 'Original Title',
                'slug' => 'original-title',
            ]);
            
            $article->title = 'Updated Title';
            $article->save();
            
            expect($article->slug)->toBe('updated-title');
        });
        
        test('preserves custom slug when title changes', function () {
            $article = Article::factory()->create([
                'title' => 'Original Title',
                'slug' => 'custom-slug',
            ]);
            
            $article->title = 'Updated Title';
            $article->save();
            
            expect($article->slug)->toBe('custom-slug');
        });
        
        test('regenerates excerpt when content changes and excerpt was auto-generated', function () {
            $article = Article::factory()->create([
                'content' => 'Original content here',
            ]);
            
            $originalExcerpt = $article->excerpt;
            
            $article->content = 'Updated content with new information';
            $article->save();
            
            expect($article->excerpt)->not->toBe($originalExcerpt);
        });
        
        test('updates meta_title when title changes', function () {
            $article = Article::factory()->create([
                'title' => 'Original Title',
                'meta_title' => 'Original Title',
            ]);
            
            $article->title = 'Updated Title';
            $article->save();
            
            expect($article->meta_title)->toBe('Updated Title');
        });
        
        test('sets published_at when status changes to published', function () {
            $article = Article::factory()->create([
                'status' => 'draft',
                'published_at' => null,
            ]);
            
            $article->status = 'published';
            $article->save();
            
            expect($article->published_at)->not->toBeNull();
        });
        
        test('clears published_at when status changes to draft', function () {
            $article = Article::factory()->create([
                'status' => 'published',
                'published_at' => now(),
            ]);
            
            $article->status = 'draft';
            $article->save();
            
            expect($article->fresh()->published_at)->toBeNull();
        });
    });
});
