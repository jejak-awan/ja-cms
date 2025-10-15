<?php

namespace Tests\Unit\Observers;

use Tests\TestCase;
use App\Modules\Page\Models\Page;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class PageObserverTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_generates_slug_from_title()
    {
        $page = Page::create([
            'title_id' => 'Test Page Title',
            'content_id' => 'Test content',
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertEquals('test-page-title', $page->slug);
    }

    /** @test */
    public function it_prevents_circular_parent_reference()
    {
        $parent = Page::create([
            'title_id' => 'Parent Page',
            'content_id' => 'Parent content',
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $child = Page::create([
            'title_id' => 'Child Page',
            'content_id' => 'Child content',
            'parent_id' => $parent->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        // Try to set parent as child of its child (circular reference)
        $parent->update(['parent_id' => $child->id]);

        $this->assertNull($parent->fresh()->parent_id);
    }

    /** @test */
    public function it_updates_hierarchy_when_parent_changes()
    {
        $parent1 = Page::create([
            'title_id' => 'Parent 1',
            'content_id' => 'Parent 1 content',
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $parent2 = Page::create([
            'title_id' => 'Parent 2',
            'content_id' => 'Parent 2 content',
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $child = Page::create([
            'title_id' => 'Child Page',
            'content_id' => 'Child content',
            'parent_id' => $parent1->id,
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $child->update(['parent_id' => $parent2->id]);

        $this->assertEquals($parent2->id, $child->fresh()->parent_id);
    }

    /** @test */
    public function it_sets_published_at_when_status_changes()
    {
        $page = Page::create([
            'title_id' => 'Test Page',
            'content_id' => 'Test content',
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertNull($page->published_at);

        $page->update(['status' => 'published']);

        $this->assertNotNull($page->fresh()->published_at);
    }

    /** @test */
    public function it_generates_meta_tags_if_empty()
    {
        $page = Page::create([
            'title_id' => 'Test Page Title',
            'content_id' => 'Test content',
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertEquals('Test Page Title', $page->meta_title);
        $this->assertNotNull($page->meta_description);
    }

    /** @test */
    public function it_clears_cache_when_updated()
    {
        Cache::put('pages.cache', 'test data');
        
        $page = Page::create([
            'title_id' => 'Test Page',
            'content_id' => 'Test content',
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $page->update(['title_id' => 'Updated Page']);

        $this->assertFalse(Cache::has('pages.cache'));
    }

    /** @test */
    public function it_prevents_self_as_parent()
    {
        $page = Page::create([
            'title_id' => 'Test Page',
            'content_id' => 'Test content',
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $page->update(['parent_id' => $page->id]);

        $this->assertNull($page->fresh()->parent_id);
    }

    /** @test */
    public function it_validates_parent_exists()
    {
        // Create a valid parent first
        $parent = Page::create([
            'title_id' => 'Valid Parent',
            'content_id' => 'Valid parent content',
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $page = Page::create([
            'title_id' => 'Test Page',
            'content_id' => 'Test content',
            'parent_id' => $parent->id, // Valid parent
            'user_id' => $this->user->id,
            'status' => 'draft'
        ]);

        $this->assertEquals($parent->id, $page->fresh()->parent_id);
    }
}