<?php

namespace App\Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'location',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function items()
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('order');
    }

    public function allItems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    public function activeItems()
    {
        return $this->hasMany(MenuItem::class)
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('order');
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    public function getTree(): array
    {
        return $this->items()
            ->with('children')
            ->where('is_active', true)
            ->get()
            ->map(function ($item) {
                return $item->toTree();
            })
            ->toArray();
    }

    // ========================================
    // STATIC METHODS
    // ========================================

    public static function findByLocation(string $location): ?Menu
    {
        return self::where('location', $location)->first();
    }

    public static function getByLocation(string $location): ?array
    {
        $menu = self::findByLocation($location);
        return $menu ? $menu->getTree() : null;
    }
}

