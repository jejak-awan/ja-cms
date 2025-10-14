<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register custom Blade directives for i18n
        $this->registerTranslationDirectives();
        $this->registerLocaleDirectives();
        $this->registerConditionalDirectives();
    }

    /**
     * Register translation-related directives
     */
    protected function registerTranslationDirectives(): void
    {
        // @t('key') - Shorthand for __()
        Blade::directive('t', function ($expression) {
            return "<?php echo __($expression); ?>";
        });

        // @te('key') - Echo escaped translation
        Blade::directive('te', function ($expression) {
            return "<?php echo e(__($expression)); ?>";
        });

        // @traw('key') - Raw translation without escaping
        Blade::directive('traw', function ($expression) {
            return "<?php echo __($expression); ?>";
        });

        // @tchoice('key', $count) - Translation with pluralization
        Blade::directive('tchoice', function ($expression) {
            return "<?php echo trans_choice($expression); ?>";
        });

        // @tfield($model, 'field') - Get translated model field
        Blade::directive('tfield', function ($expression) {
            return "<?php echo trans_field($expression); ?>";
        });
    }

    /**
     * Register locale-related directives
     */
    protected function registerLocaleDirectives(): void
    {
        // @locale - Get current locale
        Blade::directive('locale', function () {
            return "<?php echo current_locale(); ?>";
        });

        // @localeName - Get current locale name
        Blade::directive('localeName', function ($expression = null) {
            return $expression 
                ? "<?php echo locale_name($expression); ?>"
                : "<?php echo locale_name(); ?>";
        });

        // @localeFlag - Get locale flag emoji
        Blade::directive('localeFlag', function ($expression = null) {
            return $expression
                ? "<?php echo locale_flag($expression); ?>"
                : "<?php echo locale_flag(); ?>";
        });

        // @localeDir - Get text direction (ltr/rtl)
        Blade::directive('localeDir', function () {
            return "<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>";
        });

        // @formatDate($date) - Format date with locale
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo format_date_locale($expression); ?>";
        });
    }

    /**
     * Register conditional directives
     */
    protected function registerConditionalDirectives(): void
    {
        // @iflocale('id')
        Blade::if('iflocale', function ($locale) {
            return is_locale($locale);
        });

        // @ifrtl
        Blade::if('rtl', function () {
            return is_rtl();
        });

        // @ifltr
        Blade::if('ltr', function () {
            return !is_rtl();
        });

        // @ifmultilingual
        Blade::if('multilingual', function () {
            return count(active_languages()) > 1;
        });
    }
}
