<?php

namespace App\Modules\Page\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\User\Models\User;
use App\Modules\Language\Traits\Translatable;

class Page extends Model
{
    use HasFactory, Translatable;

    protected $translatable = ['title', 'content'];

    protected static function newFactory()
    {
        return \Database\Factories\PageFactory::new();
    }

    protected $fillable = [
        'title_id', 'title_en', 'slug', 'content_id', 'content_en', 'excerpt', 'template', 'featured_image',
        'status', 'order', 'is_homepage', 'parent_id', 'meta_title', 'meta_description',
        'meta_keywords', 'user_id', 'published_at', 'views', 'locale'
    ];

    protected $casts = [
        'is_homepage' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    /**
     * Accessors
     */
    public function getHasChildrenAttribute()
    {
        return $this->children()->exists();
    }

    public function getHasParentAttribute()
    {
        return !is_null($this->parent_id);
    }

    public function getUrlAttribute()
    {
        return route('public.pages.show', $this->slug);
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title_id', 'LIKE', "%{$term}%")
              ->orWhere('title_en', 'LIKE', "%{$term}%")
              ->orWhere('content_id', 'LIKE', "%{$term}%")
              ->orWhere('content_en', 'LIKE', "%{$term}%")
              ->orWhere('excerpt', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Helper Methods
     */
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

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public static function homepage()
    {
        return static::where('is_homepage', true)->published()->first();
    }

    public static function templates()
    {
        return [
            'default' => 'Default Template',
            'home' => 'Homepage Template',
            'about' => 'About Page Template',
            'contact' => 'Contact Page Template',
            'custom' => 'Custom Template'
        ];
    }
}
