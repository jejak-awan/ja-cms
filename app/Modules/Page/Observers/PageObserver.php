<?php

namespace App\Modules\Page\Observers;

use App\Modules\Page\Models\Page;
use Illuminate\Support\Str;

class PageObserver
{
    public function creating(Page $page): void
    {
        if (empty($page->slug) && !empty($page->title)) {
            $page->slug = $this->generateUniqueSlug($page->title);
        }

        if (empty($page->excerpt) && !empty($page->content)) {
            $page->excerpt = Str::limit(strip_tags($page->content), 200);
        }

        if (empty($page->meta_title) && !empty($page->title)) {
            $page->meta_title = $page->title;
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
        if ($page->isDirty('title') && !$page->isDirty('slug')) {
            $oldSlug = Str::slug($page->getOriginal('title'));
            if ($page->slug === $oldSlug) {
                $page->slug = $this->generateUniqueSlug($page->title, $page->id);
            }
        }

        if ($page->isDirty('content') && !$page->isDirty('excerpt')) {
            $page->excerpt = Str::limit(strip_tags($page->content), 200);
        }

        if ($page->isDirty('status') && $page->status === 'published' && empty($page->published_at)) {
            $page->published_at = now();
        }

        if ($page->parent_id === $page->id) {
            $page->parent_id = null;
        }
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
}
