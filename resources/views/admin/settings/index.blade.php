@extends('admin.layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Site Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Manage your site configuration</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm">
        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('general')" class="tab-btn active px-6 py-4 text-sm font-medium border-b-2 border-blue-500 text-blue-600">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    General
                </button>
                <button onclick="showTab('seo')" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    SEO
                </button>
                <button onclick="showTab('social')" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                    </svg>
                    Social Media
                </button>
                <button onclick="showTab('languages')" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    @t('admin.language_settings')
                </button>
            </nav>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- General Tab -->
            <div id="general-tab" class="tab-content">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                        <input type="text" name="settings[site_name]" value="{{ $settings['site_name'] ?? 'Laravel CMS' }}" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Tagline</label>
                        <input type="text" name="settings[site_tagline]" value="{{ $settings['site_tagline'] ?? 'Modern Content Management System' }}" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                        <textarea name="settings[site_description]" rows="3" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                            <input type="email" name="settings[site_email]" value="{{ $settings['site_email'] ?? 'admin@example.com' }}" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                            <input type="text" name="settings[site_phone]" value="{{ $settings['site_phone'] ?? '' }}" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Address</label>
                        <textarea name="settings[site_address]" rows="2" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ $settings['site_address'] ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Items Per Page</label>
                            <input type="number" name="settings[site_per_page]" value="{{ $settings['site_per_page'] ?? '10' }}" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select name="settings[site_timezone]" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="UTC" {{ ($settings['site_timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="Asia/Jakarta" {{ ($settings['site_timezone'] ?? '') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                                <option value="America/New_York" {{ ($settings['site_timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New York</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Tab -->
            <div id="seo-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="settings[seo_title]" value="{{ $settings['seo_title'] ?? '' }}" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Leave blank to use site name</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="settings[seo_description]" rows="3" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ $settings['seo_description'] ?? '' }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input type="text" name="settings[seo_keywords]" value="{{ $settings['seo_keywords'] ?? '' }}" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Comma separated keywords</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                        <input type="text" name="settings[seo_google_analytics]" value="{{ $settings['seo_google_analytics'] ?? '' }}" 
                            placeholder="G-XXXXXXXXXX"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Google Search Console</label>
                        <input type="text" name="settings[seo_google_console]" value="{{ $settings['seo_google_console'] ?? '' }}" 
                            placeholder="Verification code"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Robots Meta Tag</label>
                        <select name="settings[seo_robots]" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="index,follow" {{ ($settings['seo_robots'] ?? 'index,follow') == 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                            <option value="noindex,follow" {{ ($settings['seo_robots'] ?? '') == 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                            <option value="index,nofollow" {{ ($settings['seo_robots'] ?? '') == 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                            <option value="noindex,nofollow" {{ ($settings['seo_robots'] ?? '') == 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Social Media Tab -->
            <div id="social-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Facebook Page URL</label>
                        <input type="url" name="settings[social_facebook]" value="{{ $settings['social_facebook'] ?? '' }}" 
                            placeholder="https://facebook.com/yourpage"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Twitter/X Username</label>
                        <input type="text" name="settings[social_twitter]" value="{{ $settings['social_twitter'] ?? '' }}" 
                            placeholder="@username"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instagram Username</label>
                        <input type="text" name="settings[social_instagram]" value="{{ $settings['social_instagram'] ?? '' }}" 
                            placeholder="@username"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn Profile URL</label>
                        <input type="url" name="settings[social_linkedin]" value="{{ $settings['social_linkedin'] ?? '' }}" 
                            placeholder="https://linkedin.com/in/yourprofile"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Channel URL</label>
                        <input type="url" name="settings[social_youtube]" value="{{ $settings['social_youtube'] ?? '' }}" 
                            placeholder="https://youtube.com/c/yourchannel"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">GitHub Profile URL</label>
                        <input type="url" name="settings[social_github]" value="{{ $settings['social_github'] ?? '' }}" 
                            placeholder="https://github.com/yourusername"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Languages Tab -->
            <div id="languages-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <!-- Language Settings Form -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <h6 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.language_configuration') }}</h6>
                        </div>
                                <div class="p-6">
                                    <!-- Default Language -->
                                    <div class="mb-6">
                                        <label for="default_language" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('admin.default_language') }}
                                        </label>
                                        <select name="default_language" id="default_language" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                @php
                                    $languages = \App\Modules\Language\Models\Language::orderBy('order')->get();
                                @endphp
                                @foreach($languages as $language)
                                                <option value="{{ $language->code }}" 
                                                        {{ $language->is_default ? 'selected' : '' }}>
                                                    {{ $language->flag }} {{ $language->name }} ({{ $language->native_name }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.default_language_help') }}</p>
                                    </div>

                                    <!-- Active Languages -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('admin.active_languages') }}</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($languages as $language)
                                                <div class="flex items-center space-x-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                    <input type="checkbox" 
                                                           name="active_languages[]" 
                                                           value="{{ $language->code }}" 
                                                           id="lang_{{ $language->code }}"
                                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                           {{ $language->is_active ? 'checked' : '' }}>
                                                    <label for="lang_{{ $language->code }}" class="flex-1 cursor-pointer">
                                                        <span class="text-lg mr-2">{{ $language->flag }}</span>
                                                        <span class="font-medium text-gray-900 dark:text-white">{{ $language->name }}</span>
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">({{ $language->native_name }})</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.active_languages_help') }}</p>
                                    </div>

                                    <!-- Language Order -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('admin.language_order') }}</label>
                                        <div id="languageOrder" class="space-y-2">
                                            @foreach($languages->sortBy('order') as $language)
                                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg cursor-move hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors" 
                                                     data-language-id="{{ $language->id }}">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="text-lg">{{ $language->flag }}</span>
                                                        <div>
                                                            <span class="font-medium text-gray-900 dark:text-white">{{ $language->name }}</span>
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $language->native_name }})</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $language->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                                            {{ $language->is_active ? __('admin.active') : __('admin.inactive') }}
                                                        </span>
                                        @if($language->is_default)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                                {{ __('admin.default') }}
                                                            </span>
                                        @endif
                                                    </div>
                                                    <div class="drag-handle cursor-move p-2 hover:bg-gray-200 dark:hover:bg-gray-500 rounded">
                                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M7 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.language_order_help') }}</p>
                                    </div>

                                    <!-- Browser Detection -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('admin.browser_detection') }}</label>
                                        <div class="flex items-center space-x-3 p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                            <input type="checkbox" 
                                                   name="browser_detection" 
                                                   id="browser_detection"
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                   checked>
                                            <label for="browser_detection" class="flex-1 cursor-pointer">
                                                <span class="font-medium text-gray-900 dark:text-white">{{ __('admin.enable_browser_detection') }}</span>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('admin.browser_detection_help') }}</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Panel -->
                        <div class="space-y-6">
                            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <h6 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.language_statistics') }}</h6>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 gap-4 text-center mb-6">
                                        <div class="p-4 border-r border-gray-200 dark:border-gray-600">
                                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="totalLanguages">{{ $languages->count() }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.total_languages') }}</div>
                                        </div>
                                        <div class="p-4">
                                            <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="activeLanguages">{{ $languages->where('is_active', true)->count() }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.active_languages') }}</div>
                                        </div>
                                    </div>

                                    <!-- Enhanced Statistics -->
                                    <div class="space-y-4">
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                            <div class="text-center">
                                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2" id="totalUsers">-</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Total Users</div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                            <div class="text-center">
                                                <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2" id="languageUsage">-</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Language Usage</div>
                                            </div>
                                        </div>
                                    </div>

                        </div>
                    </div>

                            <!-- Quick Actions -->
                            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <h6 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.quick_actions') }}</h6>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-3">
                                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700 dark:hover:bg-blue-800 text-sm font-medium rounded-lg transition-colors duration-200" onclick="activateAllLanguages()">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ __('admin.activate_all') }}
                                        </button>
                                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 text-sm font-medium rounded-lg transition-colors duration-200" onclick="deactivateAllLanguages()">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ __('admin.deactivate_all') }}
                                        </button>
                                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-cyan-300 text-cyan-700 bg-cyan-50 hover:bg-cyan-100 dark:bg-cyan-900 dark:text-cyan-200 dark:border-cyan-700 dark:hover:bg-cyan-800 text-sm font-medium rounded-lg transition-colors duration-200" onclick="clearLanguageCache()">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            {{ __('admin.clear_cache') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 pt-6 border-t">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    event.target.closest('.tab-btn').classList.add('active', 'border-blue-500', 'text-blue-600');
    event.target.closest('.tab-btn').classList.remove('border-transparent', 'text-gray-500');
    
    // Initialize language settings when languages tab is shown
    if (tabName === 'languages') {
        initializeLanguageSettings();
    }
}

// Initialize language settings
function initializeLanguageSettings() {
    // Initialize sortable for language order
    const languageOrder = document.getElementById('languageOrder');
    if (languageOrder) {
        new Sortable(languageOrder, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                updateLanguageOrder();
            }
        });
    }
    
    // Load statistics
    loadLanguageStatistics();
}

// Update language order
function updateLanguageOrder() {
    const order = Array.from(document.querySelectorAll('#languageOrder [data-language-id]'))
        .map(item => item.dataset.languageId);

    fetch('{{ route("admin.settings.languages.order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ order: order })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
        }
    });
}

// Load language statistics
function loadLanguageStatistics() {
    fetch('{{ route("admin.settings.languages.statistics") }}')
        .then(response => response.json())
        .then(data => {
            // Update the enhanced statistics cards
            const totalUsersEl = document.getElementById('totalUsers');
            const languageUsageEl = document.getElementById('languageUsage');
            
            if (totalUsersEl) totalUsersEl.textContent = data.total_users || 0;
            if (languageUsageEl) languageUsageEl.textContent = data.language_usage ? Object.keys(data.language_usage).length : 0;
        })
        .catch(error => {
            console.error('Failed to load statistics:', error);
        });
}

// Language management functions
function activateAllLanguages() {
    document.querySelectorAll('input[name="active_languages[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deactivateAllLanguages() {
    document.querySelectorAll('input[name="active_languages[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function clearLanguageCache() {
    if (confirm('{{ __("admin.confirm_clear_cache") }}')) {
        fetch('{{ route("admin.settings.languages.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
            }
        });
    }
}

// Notification function
function showNotification(type, message) {
    const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full ${bgColor} border px-4 py-3 rounded-lg shadow-lg`;
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium">${message}</span>
            <button type="button" class="ml-4 text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            </div>
        `;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush
@endsection
