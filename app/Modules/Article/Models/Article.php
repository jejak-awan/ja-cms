<?php

namespace App\Modules\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Support\Translatable;

class Article extends Model
{
    use HasFactory, Translatable;

    protected $translatable = ['title', 'excerpt', 'content'];

    protected static function newFactory()
    {
        return \Database\Factories\ArticleFactory::new();
    }
    protected $fillable = [
        'title_id',
        'title_en',
        'slug',
        'excerpt_id',
        'excerpt_en',
        'content_id',
        'content_en',
        'category_id',
        'user_id',
        'featured_image',
        'status',
        'published_at',
        'featured',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'views' => 'integer',
    ];

    protected $attributes = [
        'status' => 'draft',
        'featured' => false,
        'views' => 0,
    ];


    // Boot method to register observer
    protected static function boot()
    {
        parent::boot();
        
        static::observe(\App\Modules\Article\Observers\ArticleObserver::class);
    }

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(\App\Modules\Category\Models\Category::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Modules\User\Models\User::class);
    }

    public function author()
    {
        return $this->belongsTo(\App\Modules\User\Models\User::class, 'user_id');
    }

    public function tags()
    {
        return $this->morphToMany(\App\Modules\Tag\Models\Tag::class, 'taggable');
    }


    /**
     * Scopes
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeLatest(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->orderBy($column, 'desc');
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('excerpt', 'LIKE', "%{$term}%")
              ->orWhere('content', 'LIKE', "%{$term}%")
              ->orWhere('meta_keywords', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Accessors & Mutators
     */
    public function getExcerptAttribute($value): string
    {
        // If excerpt exists, return it
        if (!empty($value)) {
            return $value;
        }
        
        // Otherwise, generate from content
        if (!empty($this->content)) {
            return Str::limit(strip_tags($this->content), 200);
        }
        
        return '';
    }

    public function getReadingTimeAttribute(): int
    {
        // Average reading speed: 200 words per minute
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200);
        
        return max(1, $minutes); // Minimum 1 minute
    }

    public function getFormattedPublishedDateAttribute(): string
    {
        return $this->published_at ? $this->published_at->format('M d, Y') : 'Not published';
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published' 
               && $this->published_at !== null 
               && $this->published_at->lte(now());
    }

    /**
     * Helper Methods
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function publish(): bool
    {
        $this->status = 'published';
        $this->published_at = $this->published_at ?? now();
        
        return $this->save();
    }

    public function unpublish(): bool
    {
        $this->status = 'draft';
        $this->published_at = null;
        
        return $this->save();
    }

    public function archive(): bool
    {
        $this->status = 'archived';
        
        return $this->save();
    }

    public function feature(): bool
    {
        $this->featured = true;
        
        return $this->save();
    }

    public function toggleFeatured(): bool
    {
        $this->featured = !$this->featured;
        
        return $this->save();
    }

    /**
     * Get URL for the article
     */
    public function getUrlAttribute(): string
    {
        return route('articles.show', $this->slug);
    }

    /**
     * Get SEO meta tags array
     */
    public function getSeoMetaAttribute(): array
    {
        return [
            'title' => $this->meta_title ?: $this->title,
            'description' => $this->meta_description ?: $this->excerpt,
            'keywords' => $this->meta_keywords ?: '',
            'image' => $this->featured_image ?: '',
            'url' => $this->url,
            'type' => 'article',
            'published_time' => $this->published_at?->toIso8601String(),
            'author' => $this->author?->name,
        ];
    }
}
