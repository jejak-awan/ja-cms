<?php

namespace App\Modules\Category\Observers;

use App\Modules\Category\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    /**
     * Handle the Category "creating" event.
     */
    public function creating(Category $category): void
    {
        // Auto-generate slug from name_id if not provided
        if (empty($category->slug) && !empty($category->name_id)) {
            $category->slug = $this->generateUniqueSlug($category->name_id);
        }

        // Auto-generate meta_title from name_id if not provided
        if (empty($category->meta_title) && !empty($category->name_id)) {
            $category->meta_title = $category->name_id;
        }

        // Auto-generate meta_description from description_id if not provided
        if (empty($category->meta_description) && !empty($category->description_id)) {
            $category->meta_description = Str::limit(strip_tags($category->description_id), 160);
        }

        // Set default order if not provided
        if (empty($category->order)) {
            $category->order = $this->getNextOrder($category->parent_id);
        }
    }

    /**
     * Handle the Category "updating" event.
     */
    public function updating(Category $category): void
    {
        // Regenerate slug if name_id changed and slug wasn't manually changed
        if ($category->isDirty('name_id') && !$category->isDirty('slug')) {
            $oldSlug = Str::slug($category->getOriginal('name_id'));
            if ($category->slug === $oldSlug) {
                $category->slug = $this->generateUniqueSlug($category->name_id, $category->id);
            }
        }

        // Update meta_title if name_id changed and meta_title wasn't manually changed
        if ($category->isDirty('name_id') && !$category->isDirty('meta_title')) {
            if (empty($category->meta_title) || $category->meta_title === $category->getOriginal('name_id')) {
                $category->meta_title = $category->name_id;
            }
        }

        // Update meta_description if description_id changed
        if ($category->isDirty('description_id') && !$category->isDirty('meta_description')) {
            $category->meta_description = Str::limit(strip_tags($category->description_id), 160);
        }

        // Prevent self-referencing parent
        if ($category->parent_id === $category->id) {
            $category->parent_id = null;
        }

        // Prevent circular parent relationships
        if ($category->parent_id && $this->hasCircularReference($category)) {
            $category->parent_id = null;
        }
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        // Clear cache when category is updated
        Cache::forget('categories.cache');
    }

    /**
     * Handle the Category "deleting" event.
     */
    public function deleting(Category $category): void
    {
        // When deleting a category with children:
        // Option 1: Move children to parent's parent (orphan handling)
        if ($category->children()->exists()) {
            $category->children()->update([
                'parent_id' => $category->parent_id
            ]);
        }

        // Option 2: Or delete all children (cascade) - handled by DB foreign key
        // No additional code needed if using onDelete('cascade')
    }

    /**
     * Generate a unique slug from the given string
     */
    private function generateUniqueSlug(string $string, ?int $ignoreId = null): string
    {
        $slug = Str::slug($string);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists in database
     */
    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $query = Category::where('slug', $slug);
        
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        
        return $query->exists();
    }

    /**
     * Get next order number for categories at the same level
     */
    private function getNextOrder(?int $parentId): int
    {
        $maxOrder = Category::where('parent_id', $parentId)->max('order');
        
        return ($maxOrder ?? -1) + 1;
    }

    /**
     * Check if setting this parent would create a circular reference
     */
    private function hasCircularReference(Category $category): bool
    {
        $parent = Category::find($category->parent_id);
        $visited = [$category->id];

        while ($parent) {
            if (in_array($parent->id, $visited)) {
                return true; // Circular reference detected
            }
            
            $visited[] = $parent->id;
            $parent = $parent->parent;
        }

        return false;
    }
}
