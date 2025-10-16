<?php

namespace App\Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\Translatable;

class Category extends Model
{
    use HasFactory, Translatable;

    protected $translatable = ['name', 'description'];

    protected static function newFactory()
    {
        return \Database\Factories\CategoryFactory::new();
    }

    protected $fillable = [
        'name_id',
        'name_en',
        'slug',
        'description_id',
        'description_en',
        'parent_id',
        'color',
        'icon',
        'order',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'parent_id' => 'integer',
        'order' => 'integer',
    ];

    protected $attributes = [
        'is_active' => true,
        'order' => 0,
    ];

    /**
     * Boot method to register observer
     */
    protected static function boot()
    {
        parent::boot();
        
        static::observe(\App\Modules\Category\Observers\CategoryObserver::class);
    }

    /**
     * Relationships
     */
    public function articles()
    {
        return $this->hasMany(\App\Modules\Article\Models\Article::class);
    }

    public function publishedArticles()
    {
        return $this->articles()->published();
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Get all descendants (children, grandchildren, etc.)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors (parent, grandparent, etc.)
     */
    public function ancestors()
    {
        $ancestors = collect();
        $parent = $this->parent;

        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function scopeWithArticleCount(Builder $query): Builder
    {
        return $query->withCount(['articles', 'publishedArticles']);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('meta_keywords', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Accessors & Mutators
     */
    public function getArticlesCountAttribute(): int
    {
        return $this->articles()->count();
    }

    public function getPublishedArticlesCountAttribute(): int
    {
        return $this->publishedArticles()->count();
    }

    public function getHasChildrenAttribute(): bool
    {
        return $this->children()->exists();
    }

    public function getHasParentAttribute(): bool
    {
        return $this->parent_id !== null;
    }

    public function getDepthAttribute(): int
    {
        return $this->ancestors()->count();
    }

    public function getBreadcrumbAttribute(): array
    {
        $breadcrumb = $this->ancestors()->reverse()->pluck('name', 'slug')->toArray();
        $breadcrumb[$this->slug] = $this->name;
        
        return $breadcrumb;
    }

    /**
     * Get full path (e.g., "Parent / Child / This")
     */
    public function getFullPathAttribute(): string
    {
        $path = $this->ancestors()->reverse()->pluck('name')->toArray();
        $path[] = $this->name;
        
        return implode(' / ', $path);
    }

    /**
     * Get URL for the category
     */
    public function getUrlAttribute(): string
    {
        return route('categories.show', $this->slug);
    }

    /**
     * Get SEO meta tags array
     */
    public function getSeoMetaAttribute(): array
    {
        return [
            'title' => $this->meta_title ?: $this->name,
            'description' => $this->meta_description ?: $this->description,
            'keywords' => $this->meta_keywords ?: '',
            'url' => $this->url,
            'type' => 'website',
        ];
    }

    /**
     * Helper Methods
     */
    public function activate(): bool
    {
        $this->is_active = true;
        return $this->save();
    }

    public function deactivate(): bool
    {
        $this->is_active = false;
        return $this->save();
    }

    public function toggleStatus(): bool
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }

    /**
     * Move category to new parent
     */
    public function moveTo(?int $newParentId): bool
    {
        if ($newParentId === $this->id) {
            return false; // Cannot be own parent
        }

        $this->parent_id = $newParentId;
        return $this->save();
    }

    /**
     * Reorder categories
     */
    public static function reorder(array $order): void
    {
        foreach ($order as $position => $categoryId) {
            static::where('id', $categoryId)->update(['order' => $position]);
        }
    }

    /**
     * Get tree structure (hierarchical)
     */
    public static function getTree(?int $parentId = null, bool $activeOnly = false): \Illuminate\Support\Collection
    {
        $query = static::where('parent_id', $parentId)->ordered();
        
        if ($activeOnly) {
            $query->active();
        }

        return $query->get()->map(function ($category) use ($activeOnly) {
            $category->children_tree = static::getTree($category->id, $activeOnly);
            return $category;
        });
    }

    /**
     * Get flat list with indentation
     */
    public static function getFlatList(?int $parentId = null, int $depth = 0, bool $activeOnly = false): \Illuminate\Support\Collection
    {
        $query = static::where('parent_id', $parentId)->ordered();
        
        if ($activeOnly) {
            $query->active();
        }

        $categories = collect();
        
        foreach ($query->get() as $category) {
            $category->depth = $depth;
            $category->indented_name = str_repeat('— ', $depth) . $category->name;
            $categories->push($category);
            
            // Add children recursively
            $children = static::getFlatList($category->id, $depth + 1, $activeOnly);
            $categories = $categories->merge($children);
        }

        return $categories;
    }
}
