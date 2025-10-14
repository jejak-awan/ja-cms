<?php

use App\Modules\Page\Models\Page;

describe('PageObserver', function () {
    
    describe('creating event', function () {
        
        test('generates slug from title when creating', function () {
            $page = Page::factory()->make([
                'title' => 'About Us Page',
                'slug' => null,
            ]);
            
            $page->save();
            
            expect($page->slug)->toBe('about-us-page');
        });
        
        test('generates unique slug when duplicate exists', function () {
            Page::factory()->create([
                'title' => 'About Us',
                'slug' => 'about-us',
            ]);
            
            $page = Page::factory()->make([
                'title' => 'About Us',
                'slug' => null,
            ]);
            
            $page->save();
            
            expect($page->slug)->toBe('about-us-1');
        });
        
        test('auto-generates excerpt from content when not provided', function () {
            $content = str_repeat('This is page content. ', 30);
            
            $page = Page::factory()->make([
                'content' => $content,
                'excerpt' => null,
            ]);
            
            $page->save();
            
            expect($page->excerpt)
                ->not->toBeNull()
                ->and(strlen($page->excerpt))->toBeLessThanOrEqual(203);
        });
        
        test('generates meta_title from title if empty', function () {
            $page = Page::factory()->make([
                'title' => 'Contact Page',
                'meta_title' => null,
            ]);
            
            $page->save();
            
            expect($page->meta_title)->toBe('Contact Page');
        });
        
        test('sets published_at when status is published', function () {
            $page = Page::factory()->make([
                'status' => 'published',
                'published_at' => null,
            ]);
            
            $page->save();
            
            expect($page->published_at)->not->toBeNull();
        });
        
        test('sets default order when not provided', function () {
            $page1 = Page::factory()->create(['order' => null]);
            $page2 = Page::factory()->create(['order' => null]);
            
            expect($page1->order)->toBe(0)
                ->and($page2->order)->toBe(1);
        });
    });
    
    describe('updating event', function () {
        
        test('regenerates slug when title changes and slug was auto-generated', function () {
            $page = Page::factory()->create([
                'title' => 'Original Title',
                'slug' => 'original-title',
            ]);
            
            $page->title = 'Updated Title';
            $page->save();
            
            expect($page->slug)->toBe('updated-title');
        });
        
        test('preserves custom slug when title changes', function () {
            $page = Page::factory()->create([
                'title' => 'Original Title',
                'slug' => 'custom-slug',
            ]);
            
            $page->title = 'Updated Title';
            $page->save();
            
            expect($page->slug)->toBe('custom-slug');
        });
        
        test('regenerates excerpt when content changes', function () {
            $page = Page::factory()->create([
                'content' => 'Original content',
            ]);
            
            $originalExcerpt = $page->excerpt;
            
            $page->content = 'Updated content with new information';
            $page->save();
            
            expect($page->excerpt)->not->toBe($originalExcerpt);
        });
        
        test('sets published_at when status changes to published', function () {
            $page = Page::factory()->create([
                'status' => 'draft',
                'published_at' => null,
            ]);
            
            $page->status = 'published';
            $page->save();
            
            expect($page->published_at)->not->toBeNull();
        });
        
        test('prevents self-referencing parent', function () {
            $page = Page::factory()->create();
            
            $page->parent_id = $page->id;
            $page->save();
            
            expect($page->fresh()->parent_id)->toBeNull();
        });
    });
    
    describe('deleting event', function () {
        
        test('moves children to parent when page deleted', function () {
            $parent = Page::factory()->create(['title' => 'Parent']);
            $page = Page::factory()->create(['title' => 'Page', 'parent_id' => $parent->id]);
            $child = Page::factory()->create(['title' => 'Child', 'parent_id' => $page->id]);
            
            $page->delete();
            
            expect($child->fresh()->parent_id)->toBe($parent->id);
        });
        
        test('sets children parent_id to null when root page deleted', function () {
            $page = Page::factory()->create(['title' => 'Page', 'parent_id' => null]);
            $child = Page::factory()->create(['title' => 'Child', 'parent_id' => $page->id]);
            
            $page->delete();
            
            expect($child->fresh()->parent_id)->toBeNull();
        });
    });
});
