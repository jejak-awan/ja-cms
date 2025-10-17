<?php

namespace App\Modules\Theme\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeCustomization extends Model
{
    protected $table = 'theme_customizations';

    protected $fillable = [
        'theme_name',
        'theme_type',
        'primary_color',
        'secondary_color',
        'accent_color',
        'sidebar_color',
        'background_color',
        'text_color',
        'border_color',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Get active theme customization for a given type
     */
    public static function active($type = 'admin')
    {
        return static::where('theme_type', $type)
            ->where('is_active', true)
            ->first() ?? static::getDefaults($type);
    }

    /**
     * Get default customization for a theme type
     */
    public static function getDefaults($type = 'admin')
    {
        return new static([
            'theme_name' => 'default',
            'theme_type' => $type,
            'primary_color' => '#3B82F6',
            'secondary_color' => '#8B5CF6',
            'accent_color' => '#EC4899',
            'sidebar_color' => '#1F2937',
            'background_color' => '#F9FAFB',
            'text_color' => '#111827',
            'border_color' => '#E5E7EB',
        ]);
    }

    /**
     * Generate CSS variables from customization
     */
    public function toCss(): string
    {
        return ":root {
    --color-primary: {$this->primary_color};
    --color-secondary: {$this->secondary_color};
    --color-accent: {$this->accent_color};
    --color-sidebar: {$this->sidebar_color};
    --color-background: {$this->background_color};
    --color-text: {$this->text_color};
    --color-border: {$this->border_color};
}";
    }
}
