<?php

namespace App\Modules\Theme\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Modules\Theme\Models\Theme;

class ThemeService
{
    /**
     * Get active theme name
     */
    public function getActiveTheme(): string
    {
        return Config::get('theme.active', 'default');
    }

    /**
     * Get active admin theme name
     */
    public function getActiveAdminTheme(): string
    {
        return Config::get('theme.admin_active', 'default');
    }

    /**
     * Set active theme
     */
    public function setActiveTheme(string $theme, string $type = 'public'): bool
    {
        if (!$this->themeExists($theme)) {
            return false;
        }

        $envFile = base_path('.env');
        $envContent = File::get($envFile);

        $envKey = $type === 'admin' ? 'ADMIN_THEME_ACTIVE' : 'THEME_ACTIVE';

        if (preg_match("/^{$envKey}=.*$/m", $envContent)) {
            $envContent = preg_replace("/^{$envKey}=.*$/m", "{$envKey}={$theme}", $envContent);
        } else {
            $envContent .= "\n{$envKey}={$theme}\n";
        }

        File::put($envFile, $envContent);
        
        return true;
    }

    /**
     * Set active admin theme
     */
    public function setActiveAdminTheme(string $theme): bool
    {
        if (!$this->adminThemeExists($theme)) {
            return false;
        }

        $envFile = base_path('.env');
        $envContent = File::get($envFile);

        if (preg_match('/^ADMIN_THEME_ACTIVE=.*$/m', $envContent)) {
            $envContent = preg_replace('/^ADMIN_THEME_ACTIVE=.*$/m', "ADMIN_THEME_ACTIVE={$theme}", $envContent);
        } else {
            $envContent .= "\nADMIN_THEME_ACTIVE={$theme}\n";
        }

        File::put($envFile, $envContent);
        
        return true;
    }

    /**
     * Get all available themes
     */
    public function getThemes(): array
    {
        return Config::get('theme.themes', []);
    }

    /**
     * Get all available admin themes
     */
    public function getAdminThemes(): array
    {
        return Config::get('theme.admin_themes', []);
    }

    /**
     * Check if theme exists
     */
    public function themeExists(string $theme): bool
    {
        $path = Config::get('theme.path') . '/' . $theme;
        return File::isDirectory($path);
    }

    /**
     * Check if admin theme exists (check assets folder)
     */
    public function adminThemeExists(string $theme): bool
    {
        $path = public_path('themes/admin/' . $theme);
        return File::isDirectory($path);
    }

    /**
     * Get theme view path (for public themes)
     */
    public function getThemeViewPath(string $view): string
    {
        return "themes.{$this->getActiveTheme()}.{$view}";
    }

    /**
     * Get theme asset path
     */
    public function getThemeAsset(string $path, string $type = 'public'): string
    {
        if ($type === 'admin') {
            return asset("themes/admin/{$this->getActiveAdminTheme()}/{$path}");
        }
        return asset("themes/public/{$this->getActiveTheme()}/{$path}");
    }

    /**
     * Get theme info
     */
    public function getThemeInfo(string $theme): ?array
    {
        $themes = $this->getThemes();
        return $themes[$theme] ?? null;
    }

    /**
     * Get admin theme info
     */
    public function getAdminThemeInfo(string $theme): ?array
    {
        $themes = $this->getAdminThemes();
        return $themes[$theme] ?? null;
    }

    /**
     * Sync themes from filesystem
     */
    public function syncThemes()
    {
        $basePath = public_path('themes');
        
        if (!File::exists($basePath)) {
            return;
        }

        // Scan admin themes
        $this->scanThemeDirectory($basePath . '/admin', 'admin');
        
        // Scan public themes
        $this->scanThemeDirectory($basePath . '/public', 'public');
    }

    /**
     * Scan theme directory
     */
    protected function scanThemeDirectory($path, $type)
    {
        if (!File::exists($path)) {
            return;
        }

        $directories = File::directories($path);

        foreach ($directories as $dir) {
            $dirName = basename($dir);
            $slug = Str::slug($dirName);
            $configFile = $dir . '/theme.json';
            
            // Read theme config if exists
            $config = [];
            if (File::exists($configFile)) {
                $config = json_decode(File::get($configFile), true);
            }

            $screenshot = null;
            
            // Check for screenshot
            if (File::exists($dir . '/screenshot.png')) {
                $screenshot = 'screenshot.png';
            } elseif (File::exists($dir . '/screenshot.jpg')) {
                $screenshot = 'screenshot.jpg';
            }

            // Create or update theme record
            Theme::updateOrCreate(
                ['slug' => $slug, 'type' => $type],
                [
                    'name' => $config['name'] ?? $dirName,
                    'version' => $config['version'] ?? '1.0.0',
                    'description' => $config['description'] ?? null,
                    'author' => $config['author'] ?? null,
                    'author_url' => $config['author_url'] ?? null,
                    'screenshot' => $screenshot,
                ]
            );
        }
    }
}
