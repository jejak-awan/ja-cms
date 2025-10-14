<?php

use App\Modules\Category\Models\Category;

describe('CategoryObserver', function () {
    
    describe('creating event', function () {
        
        test('generates slug from name when creating', function () {
            $category = Category::factory()->make([
                'name' => 'Test Category Name',
                'slug' => null,
            ]);
            
            $category->save();
            
            expect($category->slug)->toBe('test-category-name');
        });
        
        test('generates unique slug when duplicate exists', function () {
            // Create first category
            Category::factory()->create([
                'name' => 'Test Category',
                'slug' => 'test-category',
            ]);
            
            // Create second with same name
            $category = Category::factory()->make([
                'name' => 'Test Category',
                'slug' => null,
            ]);
            
            $category->save();
            
            expect($category->slug)->toBe('test-category-1');
        });
        
        test('preserves manually provided slug', function () {
            $category = Category::factory()->make([
                'name' => 'Test Category',
                'slug' => 'custom-slug',
            ]);
            
            $category->save();
            
            expect($category->slug)->toBe('custom-slug');
        });
        
        test('generates meta_title from name if empty', function () {
            $category = Category::factory()->make([
                'name' => 'Test Category',
                'meta_title' => null,
            ]);
            
            $category->save();
            
            expect($category->meta_title)->toBe('Test Category');
        });
        
        test('sets default order when not provided', function () {
            // Create first category
            $cat1 = Category::factory()->create(['order' => null]);
            
            // Create second category
            $cat2 = Category::factory()->create(['order' => null]);
            
            expect($cat1->order)->toBe(0)
                ->and($cat2->order)->toBe(1);
        });
    });
    
    describe('updating event', function () {
        
        test('regenerates slug when name changes and slug was auto-generated', function () {
            $category = Category::factory()->create([
                'name' => 'Original Name',
                'slug' => 'original-name',
            ]);
            
            $category->name = 'Updated Name';
            $category->save();
            
            expect($category->slug)->toBe('updated-name');
        });
        
        test('preserves custom slug when name changes', function () {
            $category = Category::factory()->create([
                'name' => 'Original Name',
                'slug' => 'custom-slug',
            ]);
            
            $category->name = 'Updated Name';
            $category->save();
            
            expect($category->slug)->toBe('custom-slug');
        });
        
        test('prevents self-referencing parent', function () {
            $category = Category::factory()->create();
            
            $category->parent_id = $category->id;
            $category->save();
            
            expect($category->fresh()->parent_id)->toBeNull();
        });
        
        test('prevents circular parent reference', function () {
            // Create hierarchy: A -> B -> C
            $catA = Category::factory()->create(['name' => 'A']);
            $catB = Category::factory()->create(['name' => 'B', 'parent_id' => $catA->id]);
            $catC = Category::factory()->create(['name' => 'C', 'parent_id' => $catB->id]);
            
            // Try to make A child of C (would create: A -> B -> C -> A)
            $catA->parent_id = $catC->id;
            $catA->save();
            
            // Should prevent circular reference
            expect($catA->fresh()->parent_id)->toBeNull();
        });
        
        test('updates meta_title when name changes', function () {
            $category = Category::factory()->create([
                'name' => 'Original Name',
                'meta_title' => 'Original Name',
            ]);
            
            $category->name = 'Updated Name';
            $category->save();
            
            expect($category->meta_title)->toBe('Updated Name');
        });
    });
    
    describe('deleting event', function () {
        
        test('moves children to parent when category deleted', function () {
            // Create hierarchy: Parent -> Category -> Child
            $parent = Category::factory()->create(['name' => 'Parent']);
            $category = Category::factory()->create(['name' => 'Category', 'parent_id' => $parent->id]);
            $child = Category::factory()->create(['name' => 'Child', 'parent_id' => $category->id]);
            
            // Delete middle category
            $category->delete();
            
            // Child should now be child of parent
            expect($child->fresh()->parent_id)->toBe($parent->id);
        });
        
        test('sets children parent_id to null when root category deleted', function () {
            // Create hierarchy: Category (root) -> Child
            $category = Category::factory()->create(['name' => 'Category', 'parent_id' => null]);
            $child = Category::factory()->create(['name' => 'Child', 'parent_id' => $category->id]);
            
            // Delete category
            $category->delete();
            
            // Child should become root
            expect($child->fresh()->parent_id)->toBeNull();
        });
    });
});
