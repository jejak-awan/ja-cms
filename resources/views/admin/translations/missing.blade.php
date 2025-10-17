@extends('admin.layouts.admin')

@section('title', __('Missing Translations'))

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('Missing Translations') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.translations.statistics') }}" class="btn btn-secondary">
                    {{ __('View Statistics') }}
                </a>
                <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary">
                    {{ __('Back to Overrides') }}
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    <!-- Filters -->
    <div class="card mb-6">
        <form method="GET" action="{{ route('admin.translations.missing') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Language') }}
                </label>
                <select name="locale" class="form-select">
                    <option value="">{{ __('All Languages') }}</option>
                    @foreach(\App\Modules\Language\Models\Language::active() as $locale)
                        <option value="{{ $locale->code }}" {{ request('locale') == $locale->code ? 'selected' : '' }}>
                            {{ $locale->native_name }} ({{ $locale->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Domain') }}
                </label>
                <select name="domain" class="form-select">
                    <option value="">{{ __('All Domains') }}</option>
                    @foreach($domains ?? [] as $domain)
                        <option value="{{ $domain }}" {{ request('domain') == $domain ? 'selected' : '' }}>
                            {{ $domain }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('admin.translations.missing') }}" class="btn btn-secondary">
                    {{ __('Reset') }}
                </a>
            </div>
        </form>
    </div>

    @if(empty($missing))
        <div class="card text-center py-12">
            <svg class="w-20 h-20 mx-auto mb-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                {{ __('Great! No Missing Translations') }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('All translations are complete for the selected filters') }}
            </p>
        </div>
    @else
        <!-- Missing Translations by Language -->
        @foreach($missing as $locale => $domains)
            <div class="card mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <span class="badge badge-primary mr-2">{{ strtoupper($locale) }}</span>
                                        <div class="font-semibold text-gray-900 dark:text-white mb-2">
                    <span class="mr-2">🌐</span>
                        {{ \App\Modules\Language\Models\Language::byCode($locale)?->native_name ?? $locale }}
                </div>
                    </h3>
                    <span class="badge badge-warning">
                        {{ count($domains, COUNT_RECURSIVE) - count($domains) }} {{ __('missing') }}
                    </span>
                </div>

                @foreach($domains as $domain => $keys)
                    <div class="mb-6 last:mb-0">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300">
                                <span class="badge badge-secondary mr-2">{{ $domain }}</span>
                            </h4>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ count($keys) }} {{ __('keys') }}
                            </span>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 space-y-3">
                            @foreach($keys as $key)
                                <div class="flex items-start justify-between gap-4 p-3 bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700">
                                    <div class="flex-1">
                                        <code class="text-sm font-mono text-red-600 dark:text-red-400">
                                            {{ $key }}
                                        </code>
                                        
                                        @php
                                            // Try to get English translation as reference
                                            $reference = __($key, [], $domain, 'en', false);
                                        @endphp
                                        
                                        @if($reference && $reference !== $key)
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">{{ __('English:') }}</span>
                                                {{ $reference }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('admin.translations.create', [
                                        'locale' => $locale,
                                        'domain' => $domain,
                                        'key' => $key,
                                        'reference' => $reference ?? ''
                                    ]) }}" 
                                       class="btn btn-sm btn-primary whitespace-nowrap">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        {{ __('Add') }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <!-- Bulk Actions -->
        <div class="card bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-1">
                        {{ __('Bulk Translation Tip') }}
                    </h4>
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        {{ __('You can export these missing keys, translate them in bulk, and import them back using translation overrides.') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
