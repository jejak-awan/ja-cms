@extends('admin.layouts.admin')

@section('title', 'Plugins')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Plugin Manager</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Extend your CMS functionality with plugins</p>
        </div>
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $plugins->count() }} plugins</span>
        </div>
    </div>

    <!-- Plugins Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($plugins as $plugin)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border-2 {{ $plugin->is_active ? 'border-green-500' : 'border-transparent' }}">
            <!-- Header -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-900 dark:text-white truncate">{{ $plugin->name }}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">v{{ $plugin->version }}</p>
                    </div>
                </div>
                
                @if($plugin->is_active)
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Enabled</span>
                @else
                <span class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-semibold px-2 py-1 rounded">Disabled</span>
                @endif
            </div>
            
            <!-- Description -->
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                {{ $plugin->description ?? 'No description available' }}
            </p>
            
            <!-- Author -->
            <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    By <span class="font-medium">{{ $plugin->author ?? 'Unknown' }}</span>
                </p>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center space-x-2">
                <form action="{{ route('admin.plugins.toggle', $plugin->id) }}" method="POST" class="flex-1">
                    @csrf
                    @if($plugin->is_active)
                        <button type="submit" class="w-full px-4 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-lg text-sm font-medium hover:bg-red-200 dark:hover:bg-red-800 transition">
                            Disable
                        </button>
                    @else
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">
                            Enable
                        </button>
                    @endif
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium mb-2">No Plugins Found</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm">Add plugins to extend your CMS functionality</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
