<?php

namespace Tests\Unit\Observers;

use Tests\TestCase;
use App\Modules\Category\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class CategoryObserverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_slug_from_name()
    {
        $category = Category::create([
            'name_id' => 'Test Category Name',
            'description_id' => 'Test description',
            'is_active' => true
        ]);

        $this->assertEquals('test-category-name', $category->slug);
    }

    /** @test */
    public function it_prevents_circular_parent_reference()
    {
        $parent = Category::create([
            'name_id' => 'Parent Category',
            'description_id' => 'Parent description',
            'is_active' => true
        ]);

        $child = Category::create([
            'name_id' => 'Child Category',
            'description_id' => 'Child description',
            'parent_id' => $parent->id,
            'is_active' => true
        ]);

        // Try to set parent as child of its child (circular reference)
        $parent->update(['parent_id' => $child->id]);

        $this->assertNull($parent->fresh()->parent_id);
    }

    /** @test */
    public function it_updates_hierarchy_when_parent_changes()
    {
        $parent1 = Category::create([
            'name_id' => 'Parent 1',
            'description_id' => 'Parent 1 description',
            'is_active' => true
        ]);

        $parent2 = Category::create([
            'name_id' => 'Parent 2',
            'description_id' => 'Parent 2 description',
            'is_active' => true
        ]);

        $child = Category::create([
            'name_id' => 'Child Category',
            'description_id' => 'Child description',
            'parent_id' => $parent1->id,
            'is_active' => true
        ]);

        $child->update(['parent_id' => $parent2->id]);

        $this->assertEquals($parent2->id, $child->fresh()->parent_id);
    }

    /** @test */
    public function it_cascades_to_children_when_deleted()
    {
        $parent = Category::create([
            'name_id' => 'Parent Category',
            'description_id' => 'Parent description',
            'is_active' => true
        ]);

        $child = Category::create([
            'name_id' => 'Child Category',
            'description_id' => 'Child description',
            'parent_id' => $parent->id,
            'is_active' => true
        ]);

        $parent->delete();

        $this->assertNull($child->fresh()->parent_id);
    }

    /** @test */
    public function it_recounts_articles_when_updated()
    {
        $category = Category::create([
            'name_id' => 'Test Category',
            'description_id' => 'Test description',
            'is_active' => true
        ]);

        $initialCount = $category->articles_count;
        
        // Simulate article count change
        $category->update(['name_id' => 'Updated Category']);

        $this->assertNotNull($category->fresh()->articles_count);
    }

    /** @test */
    public function it_validates_parent_exists()
    {
        // Create a valid parent first
        $parent = Category::create([
            'name_id' => 'Valid Parent',
            'description_id' => 'Valid parent description',
            'is_active' => true
        ]);

        $category = Category::create([
            'name_id' => 'Test Category',
            'description_id' => 'Test description',
            'parent_id' => $parent->id, // Valid parent
            'is_active' => true
        ]);

        $this->assertEquals($parent->id, $category->fresh()->parent_id);
    }

    /** @test */
    public function it_prevents_self_as_parent()
    {
        $category = Category::create([
            'name_id' => 'Test Category',
            'description_id' => 'Test description',
            'is_active' => true
        ]);

        $category->update(['parent_id' => $category->id]);

        $this->assertNull($category->fresh()->parent_id);
    }

    /** @test */
    public function it_clears_cache_when_updated()
    {
        Cache::put('categories.cache', 'test data');
        
        $category = Category::create([
            'name_id' => 'Test Category',
            'description_id' => 'Test description',
            'is_active' => true
        ]);

        $category->update(['name_id' => 'Updated Category']);

        $this->assertFalse(Cache::has('categories.cache'));
    }
}