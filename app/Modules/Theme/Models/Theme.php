<?php

namespace App\Modules\Theme\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'version',
        'description',
        'author',
        'author_url',
        'screenshot',
        'type',
        'is_active',
        'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array'
    ];

    /**
     * Scope for active theme
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for public themes
     */
    public function scopePublicThemes($query)
    {
        return $query->where('type', 'public');
    }

    /**
     * Scope for admin themes
     */
    public function scopeAdminThemes($query)
    {
        return $query->where('type', 'admin');
    }

    /**
     * Get theme path
     */
    public function getPathAttribute()
    {
        return public_path("themes/{$this->type}/{$this->slug}");
    }

    /**
     * Get screenshot URL
     */
    public function getScreenshotUrlAttribute()
    {
        if ($this->screenshot) {
            return asset("themes/{$this->type}/{$this->slug}/{$this->screenshot}");
        }
        return asset('themes/default-screenshot.png');
    }
}
