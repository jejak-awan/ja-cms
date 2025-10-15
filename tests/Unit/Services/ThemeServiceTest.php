<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Modules\Theme\Services\ThemeService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ThemeServiceTest extends TestCase
{
    protected ThemeService $themeService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->themeService = new ThemeService();
    }

    /** @test */
    public function it_gets_active_theme()
    {
        Config::set('theme.active', 'custom-theme');
        
        $activeTheme = $this->themeService->getActiveTheme();
        
        $this->assertEquals('custom-theme', $activeTheme);
    }

    /** @test */
    public function it_gets_active_admin_theme()
    {
        Config::set('theme.admin_active', 'admin-theme');
        
        $activeAdminTheme = $this->themeService->getActiveAdminTheme();
        
        $this->assertEquals('admin-theme', $activeAdminTheme);
    }

    /** @test */
    public function it_gets_default_theme_when_not_set()
    {
        Config::set('theme.active', 'default');
        
        $activeTheme = $this->themeService->getActiveTheme();
        
        $this->assertEquals('default', $activeTheme);
    }

    /** @test */
    public function it_gets_all_themes()
    {
        $themes = ['theme1', 'theme2', 'theme3'];
        Config::set('theme.themes', $themes);
        
        $result = $this->themeService->getThemes();
        
        $this->assertEquals($themes, $result);
    }

    /** @test */
    public function it_gets_all_admin_themes()
    {
        $adminThemes = ['admin1', 'admin2'];
        Config::set('theme.admin_themes', $adminThemes);
        
        $result = $this->themeService->getAdminThemes();
        
        $this->assertEquals($adminThemes, $result);
    }

    /** @test */
    public function it_checks_theme_exists()
    {
        Config::set('theme.path', '/fake/path');
        
        // Mock File::isDirectory to return true
        File::shouldReceive('isDirectory')
            ->with('/fake/path/test-theme')
            ->andReturn(true);
        
        $exists = $this->themeService->themeExists('test-theme');
        
        $this->assertTrue($exists);
    }

    /** @test */
    public function it_checks_admin_theme_exists()
    {
        // Mock File::isDirectory to return true
        File::shouldReceive('isDirectory')
            ->with(public_path('themes/admin/test-admin-theme'))
            ->andReturn(true);
        
        $exists = $this->themeService->adminThemeExists('test-admin-theme');
        
        $this->assertTrue($exists);
    }

    /** @test */
    public function it_gets_theme_view_path()
    {
        Config::set('theme.active', 'my-theme');
        
        $viewPath = $this->themeService->getThemeViewPath('home');
        
        $this->assertEquals('themes.my-theme.home', $viewPath);
    }

    /** @test */
    public function it_gets_theme_asset_path_for_public()
    {
        Config::set('theme.active', 'my-theme');
        
        $assetPath = $this->themeService->getThemeAsset('css/style.css', 'public');
        
        $this->assertStringContainsString('themes/public/my-theme/css/style.css', $assetPath);
    }

    /** @test */
    public function it_gets_theme_asset_path_for_admin()
    {
        Config::set('theme.admin_active', 'admin-theme');
        
        $assetPath = $this->themeService->getThemeAsset('css/admin.css', 'admin');
        
        $this->assertStringContainsString('themes/admin/admin-theme/css/admin.css', $assetPath);
    }

    /** @test */
    public function it_gets_theme_info()
    {
        $themes = [
            'theme1' => ['name' => 'Theme 1', 'version' => '1.0.0'],
            'theme2' => ['name' => 'Theme 2', 'version' => '2.0.0']
        ];
        Config::set('theme.themes', $themes);
        
        $info = $this->themeService->getThemeInfo('theme1');
        
        $this->assertEquals(['name' => 'Theme 1', 'version' => '1.0.0'], $info);
    }

    /** @test */
    public function it_returns_null_for_non_existent_theme_info()
    {
        Config::set('theme.themes', []);
        
        $info = $this->themeService->getThemeInfo('non-existent');
        
        $this->assertNull($info);
    }

    /** @test */
    public function it_gets_admin_theme_info()
    {
        $adminThemes = [
            'admin1' => ['name' => 'Admin Theme 1', 'version' => '1.0.0'],
            'admin2' => ['name' => 'Admin Theme 2', 'version' => '2.0.0']
        ];
        Config::set('theme.admin_themes', $adminThemes);
        
        $info = $this->themeService->getAdminThemeInfo('admin1');
        
        $this->assertEquals(['name' => 'Admin Theme 1', 'version' => '1.0.0'], $info);
    }
}
