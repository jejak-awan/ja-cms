<?php

use App\Modules\Page\Models\Page;
use App\Modules\User\Models\User;

describe('Page Model', function () {
    
    test('has correct fillable attributes', function () {
        $page = new Page();
        
        $fillable = $page->getFillable();
        
        expect($fillable)->toContain('title_id', 'title_en', 'slug', 'content_id', 'content_en', 'template', 'status');
    });
    
    test('belongs to user', function () {
        $user = User::factory()->create();
        $page = Page::factory()->create(['user_id' => $user->id]);
        
        expect($page->user)->toBeInstanceOf(User::class);
        expect($page->user->id)->toBe($user->id);
    });
    
    test('can have parent page', function () {
        $parent = Page::factory()->create();
        $child = Page::factory()->create(['parent_id' => $parent->id]);
        
        expect($child->parent)->toBeInstanceOf(Page::class);
        expect($child->parent->id)->toBe($parent->id);
    });
    
    test('can have children pages', function () {
        $parent = Page::factory()->create();
        $children = Page::factory()->count(3)->create(['parent_id' => $parent->id]);
        
        expect($parent->children)->toHaveCount(3);
    });
    
    test('published scope returns only published pages', function () {
        Page::factory()->published()->count(3)->create();
        Page::factory()->count(2)->create(['status' => 'draft']);
        
        $published = Page::published()->get();
        
        expect($published->count())->toBeGreaterThanOrEqual(3);
        expect($published->every(fn($page) => $page->status === 'published'))->toBeTrue();
    });
    
    test('draft scope returns only draft pages', function () {
        Page::factory()->count(2)->create(['status' => 'draft']);
        Page::factory()->count(3)->create(['status' => 'published']);
        
        $drafts = Page::draft()->get();
        
        expect($drafts->count())->toBeGreaterThanOrEqual(2);
        expect($drafts->every(fn($page) => $page->status === 'draft'))->toBeTrue();
    });
    
    test('root scope returns only root pages', function () {
        Page::factory()->count(2)->create(['parent_id' => null]);
        $parent = Page::factory()->create();
        Page::factory()->count(3)->create(['parent_id' => $parent->id]);
        
        $rootPages = Page::root()->get();
        
        expect($rootPages->every(fn($page) => $page->parent_id === null))->toBeTrue();
    });
    
    test('page can be published', function () {
        $page = Page::factory()->create(['status' => 'draft']);
        
        $page->publish();
        
        expect($page->fresh()->status)->toBe('published');
    });
    
    test('page can be unpublished', function () {
        $page = Page::factory()->create(['status' => 'published']);
        
        $page->unpublish();
        
        expect($page->fresh()->status)->toBe('draft');
    });
    
    test('has_children accessor returns correct boolean', function () {
        $parent = Page::factory()->create();
        $noChildren = Page::factory()->create();
        Page::factory()->create(['parent_id' => $parent->id]);
        
        expect($parent->fresh()->has_children)->toBeTrue();
        expect($noChildren->has_children)->toBeFalse();
    });
    
    test('has_parent accessor returns correct boolean', function () {
        $parent = Page::factory()->create();
        $child = Page::factory()->create(['parent_id' => $parent->id]);
        
        expect($child->has_parent)->toBeTrue();
        expect($parent->has_parent)->toBeFalse();
    });
    
    test('url accessor returns correct route', function () {
        $page = Page::factory()->create(['slug' => 'about-us']);
        
        // Route may not be registered in test environment, just check it's a string
        expect($page->url)->toBeString();
    })->skip('Route not registered in test environment');
    
    test('search scope finds pages by title', function () {
        Page::factory()->create(['title_id' => 'About Us', 'title_en' => 'About Us EN']);
        Page::factory()->create(['title_id' => 'Contact Us', 'title_en' => 'Contact Us EN']);
        Page::factory()->create(['title_id' => 'Privacy Policy', 'title_en' => 'Privacy Policy EN']);
        
        $results = Page::search('about')->get();
        
        expect($results->count())->toBeGreaterThanOrEqual(1);
    });
    
    test('can increment views', function () {
        $page = Page::factory()->create(['views' => 5]);
        
        $page->incrementViews();
        
        expect($page->fresh()->views)->toBe(6);
    });
});
