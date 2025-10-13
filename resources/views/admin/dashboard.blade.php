@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h2>
    <p class="text-gray-600">Here's what's happening with your site today.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Articles Stats -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Articles</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['articles']['total'] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-green-600">{{ $stats['articles']['published'] }} published</span> / 
                    <span class="text-gray-600">{{ $stats['articles']['draft'] }} draft</span>
                </p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Categories Stats -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Categories</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['categories']['total'] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-green-600">{{ $stats['categories']['active'] }} active</span>
                </p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Users Stats -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['users']['total'] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-green-600">{{ $stats['users']['active'] }} active</span> / 
                    <span class="text-purple-600">{{ $stats['users']['admins'] }} admins</span>
                </p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Media Stats -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Media Files</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['media']['total'] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-blue-600">{{ $stats['media']['images'] }} images</span> / 
                    <span class="text-gray-600">{{ number_format($stats['media']['total_size'] / 1024 / 1024, 2) }} MB</span>
                </p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Pages Stats -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Pages Stats -->
    <div class="bg-white rounded-lg shadow-md p-6">
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

    <!-- Quick Actions -->
    <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
        <div class="space-y-2">
            <a href="{{ route('admin.articles.index') }}" class="block bg-white/20 hover:bg-white/30 rounded-lg px-4 py-2 transition">
                📝 Manage Articles
            </a>
            <a href="{{ route('admin.categories.index') }}" class="block bg-white/20 hover:bg-white/30 rounded-lg px-4 py-2 transition">
                📂 Manage Categories
            </a>
            <a href="{{ route('admin.pages.index') }}" class="block bg-white/20 hover:bg-white/30 rounded-lg px-4 py-2 transition">
                📄 Manage Pages
            </a>
        </div>
    </div>

    <!-- System Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
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
<div class="bg-white rounded-lg shadow-md p-6">
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

@endsection
