@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6 md:mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}! 👋</h1>
            <p class="mt-1 text-sm md:text-base text-gray-600">Here's what's happening with your site today.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Last updated: {{ now()->format('M d, Y \a\t H:i') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
    <!-- Articles Stats -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-4 md:p-6 border border-blue-200 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-2">
                    <p class="text-blue-600 text-sm font-semibold uppercase tracking-wide">Articles</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-200 text-blue-800">
                        Total
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['articles']['total']) }}</p>
                <div class="flex items-center space-x-4 mt-3">
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">{{ $stats['articles']['published'] }} published</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                        <span class="text-xs text-gray-600">{{ $stats['articles']['draft'] }} draft</span>
                    </div>
                </div>
            </div>
            <div class="bg-blue-500 rounded-xl p-3 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Categories Stats -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-4 md:p-6 border border-green-200 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-2">
                    <p class="text-green-600 text-sm font-semibold uppercase tracking-wide">Categories</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-200 text-green-800">
                        Active
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['categories']['total']) }}</p>
                <div class="flex items-center space-x-1 mt-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-xs text-gray-600">{{ $stats['categories']['active'] }} active categories</span>
                </div>
            </div>
            <div class="bg-green-500 rounded-xl p-3 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Users Stats -->
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-4 md:p-6 border border-purple-200 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-2">
                    <p class="text-purple-600 text-sm font-semibold uppercase tracking-wide">Users</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-200 text-purple-800">
                        Total
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['users']['total']) }}</p>
                <div class="flex items-center space-x-4 mt-3">
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">{{ $stats['users']['active'] }} active</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">{{ $stats['users']['admins'] }} admins</span>
                    </div>
                </div>
            </div>
            <div class="bg-purple-500 rounded-xl p-3 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Media Stats -->
    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-lg p-4 md:p-6 border border-yellow-200 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-2">
                    <p class="text-yellow-600 text-sm font-semibold uppercase tracking-wide">Media</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800">
                        Files
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['media']['total']) }}</p>
                <div class="flex items-center space-x-4 mt-3">
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">{{ $stats['media']['images'] }} images</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                        <span class="text-xs text-gray-600">{{ number_format($stats['media']['total_size'] / 1024 / 1024, 2) }} MB</span>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-500 rounded-xl p-3 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
    <!-- Articles Growth Chart -->
    <div class="lg:col-span-1 xl:col-span-2 bg-white rounded-xl shadow-lg p-4 md:p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Articles Growth</h3>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                    Last 6 months
                </span>
            </div>
        </div>
    <div class="h-56 md:h-64 relative">
            <canvas id="articlesChart" class="w-full h-full opacity-0 transition-opacity duration-500"></canvas>
            <!-- Skeleton Loader -->
            <div id="articlesChartSkeleton" class="absolute inset-0 flex items-center justify-center">
                <div class="w-3/4">
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
            <!-- Empty State Overlay -->
            <div id="articlesChartEmpty" class="hidden absolute inset-0 flex-col items-center justify-center text-gray-500 transition-opacity duration-500 opacity-0">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-2">No article data yet</p>
                <a href="/admin/articles/create" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">Create your first article →</a>
            </div>
        </div>
    </div>

    <!-- Content Distribution Chart -->
    <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Content Distribution</h3>
        </div>
    <div class="h-56 md:h-64 relative">
            <canvas id="contentChart" class="w-full h-full opacity-0 transition-opacity duration-500"></canvas>
            <!-- Skeleton Loader -->
            <div id="contentChartSkeleton" class="absolute inset-0 flex items-center justify-center">
                <div class="w-3/4">
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
            <!-- Empty State Overlay -->
            <div id="contentChartEmpty" class="hidden absolute inset-0 flex-col items-center justify-center text-gray-500 transition-opacity duration-500 opacity-0">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 3v10a2 2 0 002 2h6a2 2 0 002-2V7H7z"></path>
                </svg>
                <p class="mt-2">No content yet</p>
                <a href="/admin/articles/create" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">Create your first article →</a>
            </div>
        </div>
    </div>
</div>

<!-- Users Registration Chart -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-4 md:p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">User Registrations</h3>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                    Monthly trend
                </span>
            </div>
        </div>
    <div class="h-56 md:h-64 relative">
            <canvas id="usersChart" class="w-full h-full opacity-0 transition-opacity duration-500"></canvas>
            <!-- Skeleton Loader -->
            <div id="usersChartSkeleton" class="absolute inset-0 flex items-center justify-center">
                <div class="w-3/4">
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
            <!-- Empty State Overlay -->
            <div id="usersChartEmpty" class="hidden absolute inset-0 flex-col items-center justify-center text-gray-500 transition-opacity duration-500 opacity-0">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <p class="mt-2">No registration data yet</p>
                <a href="/admin/users/create" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">Add your first user →</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl shadow-lg p-4 md:p-6 border border-indigo-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        
        <div class="grid grid-cols-1 gap-3">
            <a href="/admin/articles/create" class="flex items-center p-3 bg-white rounded-lg border border-indigo-200 hover:border-indigo-300 hover:shadow-md transition-all duration-200 group">
                <div class="bg-blue-500 rounded-lg p-2 group-hover:bg-blue-600 transition-colors duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900">New Article</p>
                    <p class="text-xs text-gray-500">Create a new blog post</p>
                </div>
            </a>

            <a href="/admin/pages/create" class="flex items-center p-3 bg-white rounded-lg border border-indigo-200 hover:border-indigo-300 hover:shadow-md transition-all duration-200 group">
                <div class="bg-purple-500 rounded-lg p-2 group-hover:bg-purple-600 transition-colors duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900">New Page</p>
                    <p class="text-xs text-gray-500">Create a static page</p>
                </div>
            </a>

            <a href="/admin/users/create" class="flex items-center p-3 bg-white rounded-lg border border-indigo-200 hover:border-indigo-300 hover:shadow-md transition-all duration-200 group">
                <div class="bg-green-500 rounded-lg p-2 group-hover:bg-green-600 transition-colors duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900">Add User</p>
                    <p class="text-xs text-gray-500">Invite new team member</p>
                </div>
            </a>

            <a href="/admin/media" class="flex items-center p-3 bg-white rounded-lg border border-indigo-200 hover:border-indigo-300 hover:shadow-md transition-all duration-200 group">
                <div class="bg-yellow-500 rounded-lg p-2 group-hover:bg-yellow-600 transition-colors duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900">Upload Media</p>
                    <p class="text-xs text-gray-500">Add images & files</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity & Legacy Stats -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
    <!-- Pages Stats -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pages Overview</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Total Pages</span>
                <span class="font-bold text-gray-800">{{ $stats['pages']['total'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Published</span>
                <span class="font-bold text-green-600">{{ $stats['pages']['published'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Draft</span>
                <span class="font-bold text-gray-600">{{ $stats['pages']['draft'] }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <div id="activityFeedContainer" class="bg-white rounded-xl shadow-lg p-4 md:p-6 border border-gray-200">
        <div class="activity-header flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-xs text-gray-500">Live</span>
            </div>
        </div>
        
        <div class="space-y-4">
            @forelse($activities ?? [] as $activity)
                <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                            @switch($activity['icon'])
                                @case('document-text')
                                    <svg class="w-4 h-4 text-{{ $activity['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    @break
                                @case('user-plus')
                                    <svg class="w-4 h-4 text-{{ $activity['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    @break
                                @case('photograph')
                                    <svg class="w-4 h-4 text-{{ $activity['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                            <span class="text-xs text-gray-500">{{ $activity['time']->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($activity['description'], 50) }}</p>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $activity['user'] }}
                            </span>
                            @if($activity['url'])
                                <a href="{{ $activity['url'] }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    View →
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 3v10a2 2 0 002 2h6a2 2 0 002-2V7H7z"></path>
                    </svg>
                    <p class="text-gray-500">No recent activity</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination controls will be inserted here by JS -->
    </div>

    <!-- System Info -->
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">System Info</h3>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Laravel Version</span>
                <span class="font-bold text-gray-800">{{ app()->version() }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">PHP Version</span>
                <span class="font-bold text-gray-800">{{ PHP_VERSION }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Database</span>
                <span class="font-bold text-gray-800">SQLite</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Articles -->
@if(isset($stats['articles']['recent']) && $stats['articles']['recent']->count() > 0)
<div class="bg-white rounded-lg shadow-md p-4 md:p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Articles</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($stats['articles']['recent'] as $article)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ Str::limit($article->title, 50) }}
                        </a>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                        {{ $article->user->name ?? 'Unknown' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                        {{ $article->category->name ?? 'Uncategorized' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($article->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
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
    // Data dari controller
    const chartData = @json($chartData);

    // Panggil initializer dari app.js (Vite akan handle dynamic import)
    if (typeof window.initDashboardCharts === 'function') {
        window.initDashboardCharts(chartData);
    } else {
        console.warn('initDashboardCharts not available yet. Retrying shortly...');
        setTimeout(() => {
            if (typeof window.initDashboardCharts === 'function') {
                window.initDashboardCharts(chartData);
            } else {
                console.error('Failed to initialize charts: initDashboardCharts is undefined');
            }
        }, 300);
    }

    // Initialize activity feed (loaded via Vite)
    if (typeof window.initActivityFeed === 'function') {
        window.initActivityFeed();
    } else {
        console.warn('Activity feed not yet loaded, waiting...');
        setTimeout(() => {
            if (typeof window.initActivityFeed === 'function') {
                window.initActivityFeed();
            }
        }, 500);
    }
});
</script>
@endpush

@endsection
