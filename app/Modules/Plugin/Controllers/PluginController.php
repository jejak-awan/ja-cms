<?php

namespace App\Modules\Plugin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Plugin\Models\Plugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PluginController extends Controller
{
    /**
     * Display plugins list
     */
    public function index(Request $request)
    {
        // Sync plugins from filesystem
        $this->syncPlugins();

        $plugins = Plugin::orderBy('is_active', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.plugins.index', compact('plugins'));
    }

    /**
     * Toggle plugin status
     */
    public function toggle(Request $request, $id)
    {
        $plugin = Plugin::findOrFail($id);
        
        $plugin->update(['is_active' => !$plugin->is_active]);

        $status = $plugin->is_active ? 'enabled' : 'disabled';
        
        // Clear cache
        Cache::flush();

        return redirect()->back()->with('success', "Plugin '{$plugin->name}' {$status} successfully!");
    }

    /**
     * Show plugin settings
     */
    public function settings($id)
    {
        $plugin = Plugin::findOrFail($id);
        return view('admin.plugins.settings', compact('plugin'));
    }

    /**
     * Update plugin settings
     */
    public function updateSettings(Request $request, $id)
    {
        $plugin = Plugin::findOrFail($id);
        
        $settings = $request->except(['_token', '_method']);
        $plugin->update(['settings' => $settings]);

        return redirect()->back()->with('success', 'Plugin settings updated successfully!');
    }

    /**
     * Sync plugins from filesystem
     */
    protected function syncPlugins()
    {
        $pluginsPath = app_path('Modules/Plugin');
        
        if (!File::exists($pluginsPath)) {
            return;
        }

        $directories = File::directories($pluginsPath);

        foreach ($directories as $dir) {
            $dirName = basename($dir);
            
            // Skip controllers and models folders
            if (in_array($dirName, ['Controllers', 'Models', 'Views'])) {
                continue;
            }

            $slug = Str::slug($dirName);
            $configFile = $dir . '/plugin.json';
            
            // Read plugin config if exists
            $config = [];
            if (File::exists($configFile)) {
                $config = json_decode(File::get($configFile), true);
            }

            // Create or update plugin record
            Plugin::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $config['name'] ?? $dirName,
                    'version' => $config['version'] ?? '1.0.0',
                    'description' => $config['description'] ?? null,
                    'author' => $config['author'] ?? null,
                    'author_url' => $config['author_url'] ?? null,
                ]
            );
        }
    }
}

