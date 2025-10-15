<?php

namespace App\Modules\Page\Observers;

use App\Modules\Page\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    public function creating(Page $page): void
    {
        if (empty($page->slug) && !empty($page->title_id)) {
            $page->slug = $this->generateUniqueSlug($page->title_id);
        }

        if (empty($page->excerpt) && !empty($page->content_id)) {
            $page->excerpt = Str::limit(strip_tags($page->content_id), 200);
        }

        if (empty($page->meta_title) && !empty($page->title_id)) {
            $page->meta_title = $page->title_id;
        }

        if (empty($page->meta_description) && !empty($page->excerpt)) {
            $page->meta_description = Str::limit(strip_tags($page->excerpt), 160);
        }

        if ($page->status === 'published' && empty($page->published_at)) {
            $page->published_at = now();
        }

        if (empty($page->order)) {
            $page->order = $this->getNextOrder($page->parent_id);
        }
    }

    public function updating(Page $page): void
    {
        if ($page->isDirty('title_id') && !$page->isDirty('slug')) {
            $oldSlug = Str::slug($page->getOriginal('title_id'));
            if ($page->slug === $oldSlug) {
                $page->slug = $this->generateUniqueSlug($page->title_id, $page->id);
            }
        }

        if ($page->isDirty('content_id') && !$page->isDirty('excerpt')) {
            $page->excerpt = Str::limit(strip_tags($page->content_id), 200);
        }

        if ($page->isDirty('status') && $page->status === 'published' && empty($page->published_at)) {
            $page->published_at = now();
        }

        if ($page->parent_id === $page->id) {
            $page->parent_id = null;
        }

        // Prevent circular parent reference
        if ($page->parent_id && $this->wouldCreateCircularReference($page)) {
            $page->parent_id = null;
        }
    }

    public function updated(Page $page): void
    {
        // Clear cache when page is updated
        Cache::forget('pages.cache');
    }

    public function deleting(Page $page): void
    {
        if ($page->children()->exists()) {
            $page->children()->update(['parent_id' => $page->parent_id]);
        }
    }

    private function generateUniqueSlug(string $string, ?int $ignoreId = null): string
    {
        $slug = Str::slug($string);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $query = Page::where('slug', $slug);
        if ($ignoreId) $query->where('id', '!=', $ignoreId);
        return $query->exists();
    }

    private function getNextOrder(?int $parentId): int
    {
        return (Page::where('parent_id', $parentId)->max('order') ?? -1) + 1;
    }

    private function wouldCreateCircularReference(Page $page): bool
    {
        if (!$page->parent_id) {
            return false;
        }

        $parentId = $page->parent_id;
        $visited = [$page->id];

        while ($parentId) {
            if (in_array($parentId, $visited)) {
                return true;
            }

            $visited[] = $parentId;
            $parent = Page::find($parentId);
            $parentId = $parent ? $parent->parent_id : null;
        }

        return false;
    }
}
