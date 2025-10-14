@props([
    'position' => 'bottom-right', // bottom-right, bottom-left, top-right, top-left
    'size' => 'md', // sm, md, lg
    'showLabel' => true,
])

@php
    $sizeClasses = [
        'sm' => 'text-xs px-2 py-1',
        'md' => 'text-sm px-3 py-2',
        'lg' => 'text-base px-4 py-2',
    ];

    $positionClasses = [
        'bottom-right' => 'right-0 mt-2',
        'bottom-left' => 'left-0 mt-2',
        'top-right' => 'right-0 bottom-full mb-2',
        'top-left' => 'left-0 bottom-full mb-2',
    ];

    $buttonClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $dropdownClass = $positionClasses[$position] ?? $positionClasses['bottom-right'];
    $currentLocale = current_locale();
    $availableLanguages = active_languages();

    if (!($availableLanguages instanceof \Illuminate\Support\Collection)) {
        $availableLanguages = collect($availableLanguages ?? []);
    }

    $availableLanguages = $availableLanguages->map(function ($language, $key) {
        if ($language instanceof \App\Modules\Language\Models\Language) {
            return [
                'code' => $language->code,
                'name' => $language->name,
                'native_name' => $language->native_name,
                'flag' => $language->flag,
                'direction' => $language->direction,
            ];
        }

        if (is_array($language)) {
            $code = $language['code'] ?? (is_string($key) ? $key : null);

            return [
                'code' => $code,
                'name' => $language['name'] ?? null,
                'native_name' => $language['native_name'] ?? $language['name'] ?? null,
                'flag' => $language['flag'] ?? null,
                'direction' => $language['direction'] ?? null,
            ];
        }

        if (is_string($language)) {
            return [
                'code' => $language,
                'name' => locale_name($language),
                'native_name' => locale_name($language, true),
                'flag' => locale_flag($language),
                'direction' => config("locales.supported.{$language}.direction", 'ltr'),
            ];
        }

        return null;
    })->filter()->values();

    if ($availableLanguages->isEmpty()) {
        $availableLanguages = collect(config('locales.supported', []))->map(function ($data, $code) {
            return [
                'code' => $code,
                'name' => $data['name'] ?? $code,
                'native_name' => $data['native'] ?? $data['name'] ?? $code,
                'flag' => $data['flag'] ?? locale_flag($code),
                'direction' => $data['direction'] ?? 'ltr',
            ];
        })->values();
    }

    $currentLanguage = $availableLanguages->firstWhere('code', $currentLocale);

    if (!$currentLanguage) {
        $currentLanguage = [
            'code' => $currentLocale,
            'name' => locale_name($currentLocale),
            'native_name' => locale_name($currentLocale, true),
            'flag' => locale_flag($currentLocale),
            'direction' => config("locales.supported.{$currentLocale}.direction", 'ltr'),
        ];
    }

    $currentFlag = $currentLanguage['flag'] ?? locale_flag($currentLocale);
    $currentLabel = $currentLanguage['native_name'] ?? $currentLanguage['name'] ?? locale_name($currentLocale);
@endphp

<div class="relative inline-block" x-data="{ open: false }" @click.away="open = false">
    <!-- Language Switcher Button -->
    <button 
        @click="open = !open"
        type="button"
        class="{{ $buttonClass }} flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200 font-medium text-gray-700 dark:text-gray-300"
        aria-label="{{ __('admin.switch_language') }}"
        aria-expanded="false"
        aria-haspopup="true"
    >
        <!-- Current Flag -->
    <span class="text-lg" aria-hidden="true">{{ $currentFlag }}</span>
        
        <!-- Current Language Name (Optional) -->
        @if($showLabel)
            <span class="hidden sm:inline">{{ $currentLabel }}</span>
        @endif
        
        <!-- Dropdown Arrow -->
        <svg 
            class="w-4 h-4 transition-transform duration-200"
            :class="{ 'rotate-180': open }"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute {{ $dropdownClass }} w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 py-1"
        style="display: none;"
        role="menu"
        aria-orientation="vertical"
    >
        <!-- Language Options -->
        @foreach($availableLanguages as $language)
            @php
                $code = $language['code'];
                $name = $language['native_name'] ?? $language['name'] ?? strtoupper($code);
                $flag = $language['flag'] ?? locale_flag($code);
                $isActive = $currentLocale === $code;
            @endphp
            <form method="POST" action="{{ route('locale.switch', $code) }}" class="inline-block w-full">
                @csrf
                <button
                    type="submit"
                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center space-x-3 transition-colors duration-150
                        {{ $isActive ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}"
                    role="menuitem"
                >
                    <!-- Flag -->
                    <span class="text-lg" aria-hidden="true">{{ $flag }}</span>
                    
                    <!-- Language Name -->
                    <span class="flex-1">{{ $name }}</span>
                    
                    <!-- Active Indicator -->
                    @if($isActive)
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </button>
            </form>
        @endforeach

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

        <!-- Additional Info -->
        <div class="px-4 py-2">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('admin.current_locale') }}: <span class="font-semibold">{{ strtoupper($currentLocale) }}</span>
            </p>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        // Language switcher is now handled by Alpine.js from NPM in app.js
        // No additional initialization needed since Alpine is already started
    </script>
    @endpush
@endonce
