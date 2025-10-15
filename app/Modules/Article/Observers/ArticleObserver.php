<?php

namespace App\Modules\Article\Observers;

use App\Modules\Article\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ArticleObserver
{
    /**
     * Handle the Article "creating" event.
     * Runs before saving a new article
     */
    public function creating(Article $article): void
    {
        // Auto-generate slug from title if not provided
        if (empty($article->slug) && !empty($article->title_id)) {
            $article->slug = $this->generateUniqueSlug($article->title_id);
        }

        // Auto-generate excerpt from content if not provided
        if (empty($article->excerpt_id) && !empty($article->content_id)) {
            $article->excerpt_id = $this->generateExcerpt($article->content_id);
        }

        // Auto-generate meta_title from title if not provided
        if (empty($article->meta_title) && !empty($article->title_id)) {
            $article->meta_title = $article->title_id;
        }

        // Auto-generate meta_description from excerpt if not provided
        if (empty($article->meta_description) && !empty($article->excerpt_id)) {
            $article->meta_description = Str::limit(strip_tags($article->excerpt_id), 160);
        }

        // Set published_at to now if status is published and not set
        if ($article->status === 'published' && empty($article->published_at)) {
            $article->published_at = now();
        }
    }

    /**
     * Handle the Article "updating" event.
     * Runs before updating an existing article
     */
    public function updating(Article $article): void
    {
        // Regenerate slug if title changed and slug is same as old title's slug
        if ($article->isDirty('title_id') && !$article->isDirty('slug')) {
            $oldSlug = Str::slug($article->getOriginal('title_id'));
            if ($article->slug === $oldSlug) {
                $article->slug = $this->generateUniqueSlug($article->title_id, $article->id);
            }
        }

        // Regenerate excerpt if content changed and excerpt wasn't manually changed
        if ($article->isDirty('content_id') && !$article->isDirty('excerpt_id')) {
            $article->excerpt_id = $this->generateExcerpt($article->content_id);
        }

        // Update meta_title if title changed and meta_title wasn't manually changed
        if ($article->isDirty('title_id') && !$article->isDirty('meta_title')) {
            if (empty($article->meta_title) || $article->meta_title === $article->getOriginal('title_id')) {
                $article->meta_title = $article->title_id;
            }
        }

        // Update meta_description if excerpt changed and meta_description wasn't manually changed
        if ($article->isDirty('excerpt_id') && !$article->isDirty('meta_description')) {
            $article->meta_description = Str::limit(strip_tags($article->excerpt_id), 160);
        }

        // Set published_at when status changes to published
        if ($article->isDirty('status') && $article->status === 'published' && empty($article->published_at)) {
            $article->published_at = now();
        }

        // Clear published_at when status changes to draft
        if ($article->isDirty('status') && $article->status === 'draft') {
            $article->published_at = null;
        }
    }

    /**
     * Generate a unique slug from the given string
     */
    private function generateUniqueSlug(string $string, ?int $ignoreId = null): string
    {
        $slug = Str::slug($string);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists
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
        $query = Article::where('slug', $slug);
        
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        
        return $query->exists();
    }

    /**
     * Generate excerpt from content
     * Strips HTML and limits to 200 characters
     */
    private function generateExcerpt(string $content, int $length = 200): string
    {
        // Strip all HTML tags
        $text = strip_tags($content);
        
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Limit to specified length with ellipsis
        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length);
            // Try to break at last word
            $lastSpace = mb_strrpos($text, ' ');
            if ($lastSpace !== false) {
                $text = mb_substr($text, 0, $lastSpace);
            }
            $text .= '...';
        }
        
        return $text;
    }

    /**
     * Handle the Article "saved" event.
     */
    public function saved(Article $article): void
    {
        // Clear cache when article is saved
        Cache::forget('articles.cache');
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        // Clear cache when article is deleted
        Cache::forget('articles.cache');
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }
}
