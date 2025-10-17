<?php
namespace App\Modules\Language\Support;

use Illuminate\Support\Facades\Route;

class LocalizedRouteMacros
{
	/**
	 * Register localized route macros.
	 */
	public static function register(): void
	{
		Route::macro('localized', function ($uri, $action = null) {
			$defaultLocale = config('locales.default');
			$supportedLocales = array_keys(config('locales.supported', []));
            
			foreach ($supportedLocales as $locale) {
				$prefix = $locale;
                
				// Hide default locale in URL if configured
				if (config('locales.hide_default_in_url') && $locale === $defaultLocale) {
					$prefix = '';
				}
                
				$routeAttributes = [
					'prefix' => $prefix,
					'as' => $locale . '.',
					'middleware' => ['web', 'localize.routes'],
				];
                
				Route::group($routeAttributes, function () use ($uri, $action) {
					if (str_contains($uri, '{slug}')) {
						if (str_contains($uri, '/articles/{slug}')) {
							Route::get($uri, $action)->name('articles.show');
						} elseif (str_contains($uri, '/categories/{slug}')) {
							Route::get($uri, $action)->name('categories.show');
						} elseif (str_contains($uri, '/pages/{slug}')) {
							Route::get($uri, $action)->name('pages.show');
						} else {
							Route::get($uri, $action)->name('page.show');
						}
					} elseif ($uri === '/') {
						Route::get($uri, $action)->name('home');
					} elseif ($uri === '/articles') {
						Route::get($uri, $action)->name('articles.index');
					} elseif ($uri === '/categories') {
						Route::get($uri, $action)->name('categories.index');
					} elseif ($uri === '/search') {
						Route::get($uri, $action)->name('search');
					} else {
						Route::get($uri, $action);
					}
				});
			}
		});

		Route::macro('localizedResource', function ($name, $controller, array $options = []) {
			$defaultLocale = config('locales.default');
			$supportedLocales = array_keys(config('locales.supported', []));
            
			foreach ($supportedLocales as $locale) {
				$prefix = $locale;
                
				// Hide default locale in URL if configured
				if (config('locales.hide_default_in_url') && $locale === $defaultLocale) {
					$prefix = '';
				}
                
				$routeGroup = Route::prefix($prefix)
					->name($locale . '.')
					->middleware(['web', 'localize.routes']);
                
				if ($prefix) {
					$routeGroup = $routeGroup->where('locale', $locale);
				}
                
				$routeGroup->group(function () use ($name, $controller, $options) {
					Route::resource($name, $controller, $options);
				});
			}
		});
	}
}
