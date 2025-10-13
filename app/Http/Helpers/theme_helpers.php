<?php

use App\Modules\Theme\Services\ThemeService;

if (!function_exists('theme')) {
    /**
     * Get theme service instance
     */
    function theme(): ThemeService
    {
        return app(ThemeService::class);
    }
}
if (!function_exists('active_theme')) {
    /**
     * Get active theme name
     */
    function active_theme(): string
    {
        return theme()->getActiveTheme();
    }
}

if (!function_exists('active_admin_theme')) {
    /**
     * Get active admin theme name
     */
    function active_admin_theme(): string
    {
        return theme()->getActiveAdminTheme();
    }
}

if (!function_exists('theme_view')) {
    /**
     * Get theme view path (for public themes)
     */
    function theme_view(string $view): string
    {
        return theme()->getThemeViewPath($view);
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Get theme asset path
     * 
     * @param string $path Path to asset (e.g., 'css/style.css')
     * @param string $type 'public' or 'admin'
     */
    function theme_asset(string $path, string $type = 'public'): string
    {
        return theme()->getThemeAsset($path, $type);
    }
}
