@extends('admin.layouts.admin')

@section('title', __('admin.nav.dashboard'))

@section('content')
{{-- Dashboard Header --}}
<div class="mb-6 md:mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('admin.dashboard.welcome_back') }}, {{ auth()->user()->name }} 👋</h1>
            <p class="mt-1 text-sm md:text-base text-gray-600">{{ __('admin.dashboard.subtitle') }}</p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ __('admin.dashboard.last_updated') }}: {{ now()->format('M d, Y \a\t H:i') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<x-admin.stats-grid>
    {{-- Articles Card --}}
    <x-admin.stats-card
        title="{{ __('admin.nav.articles') }}"
        value="{{ number_format($stats['articles']['total'] ?? 0) }}"
        bgClass="from-blue-500 to-blue-600"
        subtitle="{{ ($stats['articles']['published'] ?? 0) }} {{ __('admin.common.published') }}, {{ ($stats['articles']['draft'] ?? 0) }} {{ __('admin.common.draft') }}"
        icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
    />

    {{-- Categories Card --}}
    <x-admin.stats-card
        title="{{ __('admin.nav.categories') }}"
        value="{{ number_format($stats['categories']['total'] ?? 0) }}"
        bgClass="from-green-500 to-green-600"
        subtitle="{{ ($stats['categories']['active'] ?? 0) }} {{ __('admin.common.active') }}"
        icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>'
    />

    {{-- Users Card --}}
    <x-admin.stats-card
        title="{{ __('admin.nav.users') }}"
        value="{{ number_format($stats['users']['total'] ?? 0) }}"
        bgClass="from-purple-500 to-purple-600"
        subtitle="{{ ($stats['users']['active'] ?? 0) }} {{ __('admin.common.active') }}, {{ ($stats['users']['admins'] ?? 0) }} {{ __('admin.users.roles.admin') }}"
        icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>'
    />

    {{-- Media Card --}}
    <x-admin.stats-card
        title="{{ __('admin.nav.media') }}"
        value="{{ number_format($stats['media']['total'] ?? 0) }}"
        bgClass="from-yellow-500 to-yellow-600"
        subtitle="{{ ($stats['media']['images'] ?? 0) }} {{ __('admin.common.images') }}, {{ number_format(($stats['media']['total_size'] ?? 0) / 1024 / 1024, 2) }} MB"
        icon='<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'
    />
</x-admin.stats-grid>

{{-- Charts Section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
    {{-- Articles Growth Chart --}}
    <div class="lg:col-span-1 xl:col-span-2 bg-white rounded-xl shadow-lg p-4 md:p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.dashboard.articles_growth') }}</h3>
            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                {{ __('admin.dashboard.last_6_months') }}
            </span>
        </div>
        <div class="h-56 md:h-64 relative">
            <canvas id="articlesChart" class="w-full h-full opacity-0 transition-opacity duration-500"></canvas>
            <div id="articlesChartSkeleton" class="absolute inset-0 flex items-center justify-center">
                <div class="w-3/4 space-y-2">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                </div>
            </div>
            <div id="articlesChartEmpty" class="hidden absolute inset-0 flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 transition-opacity duration-500 opacity-0">
                <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-2">{{ __('admin.dashboard.no_article_data') }}</p>
            </div>
        </div>
    </div>

    {{-- Content Distribution Chart --}}
    <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.dashboard.content_distribution') }}</h3>
        </div>
        <div class="h-56 md:h-64 relative">
            <canvas id="contentChart" class="w-full h-full opacity-0 transition-opacity duration-500"></canvas>
            <div id="contentChartSkeleton" class="absolute inset-0 flex items-center justify-center">
                <div class="w-3/4 space-y-2">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                </div>
            </div>
            <div id="contentChartEmpty" class="hidden absolute inset-0 flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 transition-opacity duration-500 opacity-0">
                <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 3v10a2 2 0 002 2h6a2 2 0 002-2V7H7z"></path>
                </svg>
                <p class="mt-2">{{ __('admin.dashboard.no_content_yet') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Users & Quick Actions --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
    {{-- User Registrations Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-4 md:p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.dashboard.user_registrations') }}</h3>
            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                {{ __('admin.dashboard.monthly_trend') }}
            </span>
        </div>
        <div class="h-56 md:h-64 relative">
            <canvas id="usersChart" class="w-full h-full opacity-0 transition-opacity duration-500"></canvas>
            <div id="usersChartSkeleton" class="absolute inset-0 flex items-center justify-center">
                <div class="w-3/4 space-y-2">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                </div>
            </div>
            <div id="usersChartEmpty" class="hidden absolute inset-0 flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 transition-opacity duration-500 opacity-0">
                <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <p class="mt-2">{{ __('admin.dashboard.no_registration_data') }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions Panel --}}
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-xl shadow-lg p-4 md:p-6 border border-indigo-200 dark:border-indigo-700/50">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.dashboard.quick_actions') }}</h3>
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        
        <div class="grid grid-cols-1 gap-3">
            <a href="{{ route('admin.articles.create') }}" class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-indigo-200 dark:border-indigo-700/50 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-md transition-all duration-200 group">
                <div class="bg-blue-500 rounded-lg p-2 group-hover:bg-blue-600 transition-colors duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900 dark:text-white">{{ __('admin.articles.new_article') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('admin.articles.create_new_blog_post') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.pages.create') }}" class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-indigo-200 dark:border-indigo-700/50 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-md transition-all duration-200 group">
                <div class="bg-purple-500 rounded-lg p-2 group-hover:bg-purple-600 transition-colors duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900 dark:text-white">{{ __('admin.pages.new_page') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('admin.pages.create_static_page') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.users.create') }}" class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-indigo-200 dark:border-indigo-700/50 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-md transition-all duration-200 group">
                <div class="bg-green-500 rounded-lg p-2 group-hover:bg-green-600 transition-colors duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900 dark:text-white">{{ __('admin.users.add_user') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('admin.users.invite_new_team_member') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.media.index') }}" class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-indigo-200 dark:border-indigo-700/50 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-md transition-all duration-200 group">
                <div class="bg-yellow-500 rounded-lg p-2 group-hover:bg-yellow-600 transition-colors duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900 dark:text-white">{{ __('admin.media.upload_media') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('admin.media.add_images_files') }}</p>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- Bottom Section: Activity, Overview, etc --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
    {{-- Recent Activity --}}
    <div id="activityFeedContainer" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        <div class="activity-header flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.dashboard.recent_activity') }}</h3>
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('admin.dashboard.live') }}</span>
            </div>
        </div>
        
        <div class="activity-list grid grid-cols-1 gap-3">
            @forelse($activities ?? [] as $activity)
                <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['title'] }}</p>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['time']->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($activity['description'], 50) }}</p>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                {{ $activity['user'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <x-admin.empty-state
                    title="{{ __('admin.dashboard.no_recent_activity') }}"
                    description="{{ __('admin.dashboard.activity_will_appear_here') }}"
                />
            @endforelse
        </div>
    </div>

    {{-- Pages & System Info --}}
    <div class="space-y-4 md:gap-6">
        {{-- Pages Overview --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 md:p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.dashboard.pages_overview') }}</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('admin.dashboard.total_pages') }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $stats['pages']['total'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('admin.dashboard.published') }}</span>
                    <span class="font-bold text-green-600 dark:text-green-400">{{ $stats['pages']['published'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('admin.dashboard.draft') }}</span>
                    <span class="font-bold text-gray-600 dark:text-gray-400">{{ $stats['pages']['draft'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- System Info --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 md:p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.dashboard.system_info') }}</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('admin.dashboard.laravel_version') }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('admin.dashboard.php_version') }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('admin.dashboard.database') }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ __('admin.dashboard.sqlite') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Articles Table --}}
@if(isset($stats['articles']['recent']) && $stats['articles']['recent']->count() > 0)
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 md:p-6 border border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.dashboard.recent_articles') }}</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.dashboard.title') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.dashboard.author') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.dashboard.category') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.dashboard.status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.dashboard.date') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($stats['articles']['recent'] as $article)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                            {{ Str::limit($article->title, 50) }}
                        </a>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                        {{ $article->user->name ?? __('admin.dashboard.unknown') }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                        {{ $article->category->name ?? __('admin.dashboard.uncategorized') }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $article->status === 'published' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                            {{ ucfirst($article->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                        {{ $article->created_at->diffForHumans() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);
    
    if (typeof window.initDashboardCharts === 'function') {
        window.initDashboardCharts(chartData);
    } else {
        console.warn('initDashboardCharts not available yet');
        setTimeout(() => {
            if (typeof window.initDashboardCharts === 'function') {
                window.initDashboardCharts(chartData);
            }
        }, 300);
    }
    
    if (typeof window.initActivityFeed === 'function') {
        window.initActivityFeed();
    }
});
</script>
@endpush

@endsection
