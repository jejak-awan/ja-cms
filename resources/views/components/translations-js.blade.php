{{-- Translation Export to JavaScript Component --}}
@props(['domains' => ['default'], 'variable' => 'translations'])

@php
    $locale = app()->getLocale();
    $translations = [];
    
    foreach ($domains as $domain) {
        $trans = App\Modules\Language\Services\TranslationService::exportToJson($domain, $locale);
        $translations[$domain] = json_decode($trans, true);
    }
@endphp

<script>
    window.{{ $variable }} = @json($translations);
    window.currentLocale = '{{ $locale }}';
    window.fallbackLocale = '{{ config('app.fallback_locale') }}';
    
    // Helper function for JavaScript translations
    window.trans = function(key, domain = 'default', replacements = {}) {
        let translation = window.{{ $variable }}[domain]?.[key] || key;
        
        // Replace parameters
        Object.keys(replacements).forEach(param => {
            translation = translation.replace(`:${param}`, replacements[param]);
        });
        
        return translation;
    };
    
    // Alias for shorter usage
    window.__ = window.trans;
</script>
