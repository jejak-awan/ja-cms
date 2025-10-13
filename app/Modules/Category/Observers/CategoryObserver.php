<?php

namespace App\Modules\Category\Observers;

use App\Modules\Category\Models\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    /**
     * Handle the Category "creating" event.
     */
    public function creating(Category $category): void
    {
        // Auto-generate slug from name if not provided
        if (empty($category->slug) && !empty($category->name)) {
            $category->slug = $this->generateUniqueSlug($category->name);
        }

        // Auto-generate meta_title from name if not provided
        if (empty($category->meta_title) && !empty($category->name)) {
            $category->meta_title = $category->name;
        }

        // Auto-generate meta_description from description if not provided
        if (empty($category->meta_description) && !empty($category->description)) {
            $category->meta_description = Str::limit(strip_tags($category->description), 160);
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
        // Regenerate slug if name changed and slug wasn't manually changed
        if ($category->isDirty('name') && !$category->isDirty('slug')) {
            $oldSlug = Str::slug($category->getOriginal('name'));
            if ($category->slug === $oldSlug) {
                $category->slug = $this->generateUniqueSlug($category->name, $category->id);
            }
        }

        // Update meta_title if name changed and meta_title wasn't manually changed
        if ($category->isDirty('name') && !$category->isDirty('meta_title')) {
            if (empty($category->meta_title) || $category->meta_title === $category->getOriginal('name')) {
                $category->meta_title = $category->name;
            }
        }

        // Update meta_description if description changed
        if ($category->isDirty('description') && !$category->isDirty('meta_description')) {
            $category->meta_description = Str::limit(strip_tags($category->description), 160);
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
