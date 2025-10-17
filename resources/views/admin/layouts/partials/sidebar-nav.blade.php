{{-- Sidebar Navigation Menu --}}
    {{-- Dashboard --}}
    <a href="/admin" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }} focus:outline-none focus:ring-2 focus:ring-blue-500" tabindex="0" aria-label="Dashboard">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <span class="font-medium">Dashboard</span>
    </a>
    
    {{-- Content --}}
    <div class="mt-6 mb-3">
        <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Content</h3>
    </div>
    <div class="mb-2">
        <button onclick="toggleDropdown('content-dropdown')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all hover:bg-gray-700 group focus:outline-none focus:ring-2 focus:ring-blue-500" tabindex="0" aria-label="Content Menu" aria-expanded="false">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="font-medium">Content</span>
            </div>
            <svg id="content-dropdown-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="content-dropdown" class="hidden ml-4 mt-1 space-y-1">
            <a href="/admin/articles" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/articles*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Articles</span>
            </a>
            <a href="/admin/pages" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/pages*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span>Pages</span>
            </a>
            <a href="/admin/categories" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/categories*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <span>Categories</span>
            </a>
            <a href="/admin/media" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/media*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Media Library</span>
            </a>
        </div>
    </div>

    {{-- Users --}}
    <div class="mt-6 mb-3">
        <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Users</h3>
    </div>
    <div class="mb-2">
        <button onclick="toggleDropdown('users-dropdown')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all hover:bg-gray-700 group">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="font-medium">Users</span>
            </div>
            <svg id="users-dropdown-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="users-dropdown" class="hidden ml-4 mt-1 space-y-1">
            <a href="/admin/users" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/users*') && !request()->is('admin/users/import-export') && !request()->is('admin/users/search') && !request()->is('admin/users/statistics') && !request()->is('admin/users/activity-logs') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span>All Users</span>
            </a>
            <a href="/admin/roles" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/roles*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>Roles & Permissions</span>
            </a>
            <a href="/admin/users/import-export" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/users/import-export*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <span>Import/Export</span>
            </a>
            <a href="/admin/users/search" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/users/search*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span>Advanced Search</span>
            </a>
            <a href="/admin/users/statistics" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/users/statistics*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>User Statistics</span>
            </a>
            <a href="/admin/profile" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/profile*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>My Profile</span>
            </a>
        </div>
    </div>

    {{-- Preferences --}}
    <div class="mt-6 mb-3">
        <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Preferences</h3>
    </div>
    <div class="mb-2">
        <button onclick="toggleDropdown('preferences-dropdown')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all hover:bg-gray-700 group">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">Preferences</span>
            </div>
            <svg id="preferences-dropdown-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="preferences-dropdown" class="hidden ml-4 mt-1 space-y-1">
            <a href="/admin/settings" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/settings*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Site Settings</span>
            </a>
            <a href="/admin/menus" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/menus*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <span>Navigation Menus</span>
            </a>
            <a href="/admin/themes" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/themes*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                </svg>
                <span>Themes</span>
            </a>
        </div>
    </div>

    {{-- Translations --}}
    <div class="mt-6 mb-3">
        <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Translations</h3>
    </div>
    <div class="mb-2">
        <button onclick="toggleDropdown('translations-dropdown')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all hover:bg-gray-700 group">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                <span class="font-medium">Translations</span>
            </div>
            <svg id="translations-dropdown-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="translations-dropdown" class="hidden ml-4 mt-1 space-y-1">
            <a href="{{ route('admin.translations.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/translations') || request()->is('admin/translations/create') || request()->is('admin/translations/*/edit') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Translation Overrides</span>
            </a>
            <a href="{{ route('admin.translations.statistics') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/translations/statistics*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Statistics</span>
            </a>
            <a href="{{ route('admin.translations.missing') }}" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/translations/missing*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Missing Translations</span>
            </a>
        </div>
    </div>

    {{-- System & Tools --}}
    <div class="mt-6 mb-3">
        <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">System & Tools</h3>
    </div>
    <div class="mb-2">
        <button onclick="toggleDropdown('system-dropdown')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all hover:bg-gray-700 group">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                </svg>
                <span class="font-medium">System & Tools</span>
            </div>
            <svg id="system-dropdown-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="system-dropdown" class="hidden ml-4 mt-1 space-y-1">
            <a href="/admin/performance" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/performance*') || request()->is('admin/cache*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span>Performance</span>
            </a>
            <a href="/admin/security/two-factor" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/security*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>Security</span>
            </a>
            <a href="/admin/users/activity-logs" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/users/activity-logs*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span>Activity Logs</span>
            </a>
            <a href="/admin/plugins" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/plugins*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
                <span>Plugins</span>
            </a>
        </div>
    </div>
