<?php

namespace App\Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\MenuItemFactory::new();
    }
    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'route',
        'type',
        'target_id',
        'target',
        'icon',
        'css_class',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'target_id' => 'integer',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getResolvedUrlAttribute(): string
    {
        if ($this->url) {
            return $this->url;
        }

        if ($this->route) {
            try {
                return route($this->route);
            } catch (\Exception $e) {
                return '#';
            }
        }

        if ($this->type && $this->reference_id) {
            try {
                return $this->resolveReferenceUrl();
            } catch (\Exception $e) {
                return '#';
            }
        }

        return '#';
    }

    public function getHasChildrenAttribute(): bool
    {
        return $this->children()->exists();
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    private function resolveReferenceUrl(): string
    {
        return match ($this->type) {
            'page' => \App\Modules\Page\Models\Page::find($this->reference_id)?->url ?? '#',
            'article' => \App\Modules\Article\Models\Article::find($this->reference_id)?->url ?? '#',
            'category' => \App\Modules\Category\Models\Category::find($this->reference_id)?->url ?? '#',
            default => '#',
        };
    }

    public function toTree(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->resolved_url,
            'target' => $this->target,
            'icon' => $this->icon,
            'has_children' => $this->has_children,
            'children' => $this->children()
                ->active()
                ->get()
                ->map(fn($child) => $child->toTree())
                ->toArray(),
        ];
    }
}
