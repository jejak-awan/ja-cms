@extends('admin.layouts.admin')

@section('title', 'Themes')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Theme Manager</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage themes for admin panel and public website</p>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <!-- Tab Headers -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button onclick="switchTab('admin')" id="tab-admin" class="tab-button active group inline-flex items-center px-6 py-4 border-b-2 border-blue-500 font-medium text-sm text-blue-600 dark:text-blue-400">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Admin Panel Themes
                    <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">{{ $adminThemes->count() }}</span>
                </button>
                <button onclick="switchTab('public')" id="tab-public" class="tab-button group inline-flex items-center px-6 py-4 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    Public Website Themes
                    <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">{{ $publicThemes->count() }}</span>
                </button>
            </nav>
        </div>

        <!-- Tab Content: Admin Themes -->
        <div id="content-admin" class="tab-content p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($adminThemes as $theme)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden border-2 {{ $theme->is_active ? 'border-blue-500' : 'border-transparent' }}">
                    <!-- Screenshot -->
                    <div class="aspect-video bg-gradient-to-br from-gray-900 to-gray-700 flex items-center justify-center relative">
                        @if($theme->screenshot)
                            <img src="{{ $theme->screenshot_url }}" alt="{{ $theme->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-white text-sm mt-2 block">{{ $theme->name }}</span>
                            </div>
                        @endif
                        
                        @if($theme->is_active)
                        <div class="absolute top-2 right-2">
                            <span class="bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full">Active</span>
                        </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <h5 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $theme->name }}</h5>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">v{{ $theme->version }} by {{ $theme->author ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $theme->description ?? 'No description' }}</p>
                        
                        <div class="flex space-x-2">
                            @if($theme->is_active)
                                <button disabled class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                                    Active
                                </button>
                            @else
                                <form action="{{ route('admin.themes.activate', $theme->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                                        Activate
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">No admin themes found</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Tab Content: Public Themes -->
        <div id="content-public" class="tab-content hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($publicThemes as $theme)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden border-2 {{ $theme->is_active ? 'border-green-500' : 'border-transparent' }}">
                    <!-- Screenshot -->
                    <div class="aspect-video bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center relative">
                        @if($theme->screenshot)
                            <img src="{{ $theme->screenshot_url }}" alt="{{ $theme->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-white text-sm mt-2 block">{{ $theme->name }}</span>
                            </div>
                        @endif
                        
                        @if($theme->is_active)
                        <div class="absolute top-2 right-2">
                            <span class="bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full">Active</span>
                        </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <h5 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $theme->name }}</h5>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">v{{ $theme->version }} by {{ $theme->author ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $theme->description ?? 'No description' }}</p>
                        
                        <div class="flex space-x-2">
                            @if($theme->is_active)
                                <button disabled class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                                    Active
                                </button>
                            @else
                                <form action="{{ route('admin.themes.activate', $theme->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">
                                        Activate
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">No public themes found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'border-green-500', 'text-blue-600', 'text-green-600', 'dark:text-blue-400', 'dark:text-green-400');
        button.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    activeButton.classList.add('active');
    
    if (tabName === 'admin') {
        activeButton.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
    } else {
        activeButton.classList.add('border-green-500', 'text-green-600', 'dark:text-green-400');
    }
}
</script>
@endsection


@section('title', 'Themes')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Theme Manager</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage themes for admin panel and public website</p>
    </div>

    <!-- Admin Themes Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Admin Panel Themes</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Customize the look of your admin panel</p>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $adminThemes->count() }} themes</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($adminThemes as $theme)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden border-2 {{ $theme->is_active ? 'border-blue-500' : 'border-transparent' }}">
                <!-- Screenshot -->
                <div class="aspect-video bg-gradient-to-br from-gray-900 to-gray-700 flex items-center justify-center relative">
                    @if($theme->screenshot)
                        <img src="{{ $theme->screenshot_url }}" alt="{{ $theme->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-white text-sm mt-2 block">{{ $theme->name }}</span>
                        </div>
                    @endif
                    
                    @if($theme->is_active)
                    <div class="absolute top-2 right-2">
                        <span class="bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full">Active</span>
                    </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="p-4">
                    <h5 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $theme->name }}</h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">v{{ $theme->version }} by {{ $theme->author ?? 'Unknown' }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $theme->description ?? 'No description' }}</p>
                    
                    <div class="flex space-x-2">
                        @if($theme->is_active)
                            <button disabled class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                                Active
                            </button>
                        @else
                            <form action="{{ route('admin.themes.activate', $theme->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                                    Activate
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">No admin themes found</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Public Themes Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Public Website Themes</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Customize the look of your public website</p>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $publicThemes->count() }} themes</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($publicThemes as $theme)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden border-2 {{ $theme->is_active ? 'border-green-500' : 'border-transparent' }}">
                <!-- Screenshot -->
                <div class="aspect-video bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center relative">
                    @if($theme->screenshot)
                        <img src="{{ $theme->screenshot_url }}" alt="{{ $theme->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-white text-sm mt-2 block">{{ $theme->name }}</span>
                        </div>
                    @endif
                    
                    @if($theme->is_active)
                    <div class="absolute top-2 right-2">
                        <span class="bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full">Active</span>
                    </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="p-4">
                    <h5 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $theme->name }}</h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">v{{ $theme->version }} by {{ $theme->author ?? 'Unknown' }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $theme->description ?? 'No description' }}</p>
                    
                    <div class="flex space-x-2">
                        @if($theme->is_active)
                            <button disabled class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                                Active
                            </button>
                        @else
                            <form action="{{ route('admin.themes.activate', $theme->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">
                                    Activate
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">No public themes found</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
