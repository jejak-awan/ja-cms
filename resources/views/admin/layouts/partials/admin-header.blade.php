{{-- Admin Header --}}
<header class="bg-white shadow-md z-10 dark:bg-gray-800" style="height: 73px;">
    <div class="flex items-center justify-between px-4 md:px-8 h-full">
        <div class="flex items-center space-x-4">
            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-button" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-100">@yield('title', 'Dashboard')</h2>
                <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1 hidden md:block">Welcome back, {{ auth()->user()->name ?? 'Admin' }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-2 md:space-x-4">
            {{-- Dark Mode Toggle --}}
            <button id="theme-toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Toggle Dark Mode">
                <svg id="theme-icon-light" class="w-5 h-5 text-gray-600 dark:text-gray-300 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <svg id="theme-icon-dark" class="w-5 h-5 text-gray-600 dark:text-gray-300 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
            
            {{-- Notifications Dropdown --}}
            <div class="relative">
                <button id="notifications-button" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition relative">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                
                {{-- Notifications Dropdown Menu --}}
                <div id="notifications-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="#" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">New article published</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">John Doe published "Getting Started with Laravel"</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">2 minutes ago</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                        <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">View all notifications</a>
                    </div>
                </div>
            </div>
            
            {{-- Language Switcher --}}
            <x-language-switcher />

            {{-- View Site Button (Hidden on mobile) --}}
            <a href="/" class="hidden md:flex items-center space-x-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition text-gray-700 dark:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                <span class="text-sm font-medium">@t('admin.nav.view_site')</span>
            </a>
            
            {{-- User Profile Dropdown --}}
            <div class="relative">
                <button id="user-profile-button" class="flex items-center space-x-2 md:space-x-3 px-2 md:px-4 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm md:text-lg shadow-lg">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                    </div>
                    <svg class="hidden md:block w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                {{-- User Dropdown Menu --}}
                <div id="user-profile-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                    </div>
                    <div class="py-2">
                        <a href="{{ route('admin.users.profile.show', auth()->id()) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            My Profile
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                        <a href="{{ route('admin.security.two-factor') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Security
                        </a>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 py-2">
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="button" onclick="confirmLogout()" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
