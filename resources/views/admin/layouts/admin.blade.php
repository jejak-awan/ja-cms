<!DOCTYPE html>
<html lang="en" data-theme="{{ active_admin_theme() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Laravel CMS</title>
    
    <!-- Base Tailwind CSS -->
    @vite('resources/css/app.css')
    
    <!-- Theme-specific CSS (if exists) -->
    @if(file_exists(public_path('themes/admin/' . active_admin_theme() . '/css/style.css')))
        <link rel="stylesheet" href="{{ asset('themes/admin/' . active_admin_theme() . '/css/style.css') }}">
    @endif
    
    @stack('styles')
    
    <style>
        body {
            overflow-x: hidden;
        }
        
        /* Dark mode styles */
        [data-theme="dark"] {
            --bg-primary: #1a202c;
            --bg-secondary: #2d3748;
            --text-primary: #f7fafc;
            --text-secondary: #cbd5e0;
        }
        
        .dark body {
            background-color: #111827;
            color: #f3f4f6;
        }
        
        .dark .bg-white {
            background-color: #1f2937;
        }
        
        .dark .text-gray-900 {
            color: #f3f4f6;
        }
        
        .dark .text-gray-600 {
            color: #9ca3af;
        }
        
        .dark .text-gray-500 {
            color: #6b7280;
        }
        
        .dark .border-gray-200 {
            border-color: #374151;
        }
        
        .dark .bg-gray-50 {
            background-color: #111827;
        }
        
        .dark .bg-gray-100 {
            background-color: #1f2937;
        }
        
        .dark .hover\:bg-gray-200:hover {
            background-color: #374151;
        }
        
        /* Sidebar mobile */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .sidebar.open {
            transform: translateX(0);
        }
        
        @media (min-width: 769px) {
            .sidebar {
                position: relative;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased theme-{{ active_admin_theme() }}">
    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex-shrink-0 shadow-xl z-50">
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">Laravel CMS</h1>
                <p class="text-xs text-gray-400 mt-1">Admin Panel</p>
            </div>
            
            <nav class="mt-4 px-3 overflow-y-auto" style="max-height: calc(100vh - 120px);">
                <a href="/admin" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="/admin/articles" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin/articles*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">Articles</span>
                </a>
                
                <a href="/admin/categories" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin/categories*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="font-medium">Categories</span>
                </a>
                
                <a href="/admin/pages" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin/pages*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">Pages</span>
                </a>
                
                <a href="/admin/media" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin/media*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">Media</span>
                </a>
                
                <!-- User Management Dropdown -->
                <div class="mt-6 mb-3">
                    <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User Management</h3>
                </div>
                <div class="mb-2">
                    <button onclick="toggleDropdown('user-dropdown')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all hover:bg-gray-700 group">
                        <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h-10a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2zM9 10h.01M9 14h.01M13 10h.01M13 14h.01M17 10h.01M17 14h.01"></path>
                            </svg>
                            <span class="font-medium">Users</span>
                        </div>
                        <svg id="user-dropdown-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="user-dropdown" class="hidden ml-4 mt-1 space-y-1">
                        <a href="/admin/users" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/users*') && !request()->is('admin/users/create') && !request()->is('admin/profile*') && !request()->is('admin/roles*') && !request()->is('admin/users/activity-logs*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                            <span>All Users</span>
                        </a>
                        <a href="/admin/users/create" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/users/create') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Add User</span>
                        </a>
                        <a href="/admin/profile" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/profile*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>My Profile</span>
                        </a>
                        <a href="/admin/roles" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/roles*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Roles & Permissions</span>
                        </a>
                        <a href="/admin/users/activity-logs" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/users/activity-logs*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <span>Activity Logs</span>
                        </a>
                        <a href="/admin/users/import-export" class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->is('admin/users/import-export*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
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
                            <span>Statistics</span>
                        </a>
                    </div>
                </div>
                
                <a href="/admin/menus" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin/menus*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="font-medium">Menus</span>
                </a>
                
                <a href="/admin/settings" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin/settings*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-medium">Settings</span>
                </a>
                
                <div class="border-t border-gray-700 my-3 mx-4"></div>
                
                <a href="/admin/themes" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin/themes*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    <span class="font-medium">Themes</span>
                </a>
                
                <a href="/admin/plugins" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all {{ request()->is('admin/plugins*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                    </svg>
                    <span class="font-medium">Plugins</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-md z-10 dark:bg-gray-800">
                <div class="flex items-center justify-between px-4 md:px-8 py-4">
                    <div class="flex items-center space-x-4">
                        <!-- Mobile Menu Button -->
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
                        <!-- Dark Mode Toggle -->
                        <button id="theme-toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Toggle Dark Mode">
                            <svg id="theme-icon-light" class="w-5 h-5 text-gray-600 dark:text-gray-300 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg id="theme-icon-dark" class="w-5 h-5 text-gray-600 dark:text-gray-300 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div class="relative">
                            <button id="notifications-button" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition relative">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Notifications Dropdown Menu -->
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
                                    <a href="#" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">New user registered</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jane Smith created an account</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">1 hour ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-gray-300 rounded-full mt-2"></div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">System update available</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Version 1.1.0 is ready to install</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">1 day ago</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">View all notifications</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- View Site Button (Hidden on mobile) -->
                        <a href="/" class="hidden md:flex items-center space-x-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition text-gray-700 dark:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            <span class="text-sm font-medium">View Site</span>
                        </a>
                        
                        <!-- User Dropdown (Simplified for mobile) -->
                        <div class="flex items-center space-x-2 md:space-x-3 px-2 md:px-4 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm md:text-lg shadow-lg">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="hidden md:block">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                            </div>
                        </div>
                        
                        <!-- Logout Button -->
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="button" onclick="confirmLogout()" class="p-2 text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Logout">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 md:p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-3 px-4 md:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between text-xs md:text-sm text-gray-600 dark:text-gray-400 space-y-2 md:space-y-0">
                    <span>&copy; {{ date('Y') }} Laravel CMS. All rights reserved.</span>
                    <span>Version 1.0.0</span>
                </div>
            </footer>
        </div>
    </div>

    <div id="app"></div>
    
    <!-- Base Vue/JS -->
    @vite(['resources/js/app.js', 'resources/js/activity-feed.js'])
    
    <!-- Theme-specific JS (if exists) -->
    @if(file_exists(public_path('themes/admin/' . active_admin_theme() . '/js/script.js')))
        <script src="{{ asset('themes/admin/' . active_admin_theme() . '/js/script.js') }}"></script>
    @endif
    
    @stack('scripts')
    
    <script>
    // Mobile Menu Toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('sidebar');
    const mobileOverlay = document.getElementById('mobile-overlay');
    
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            mobileOverlay.classList.toggle('hidden');
        });
        
        mobileOverlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            mobileOverlay.classList.add('hidden');
        });
    }
    
    // Notifications Dropdown
    const notificationsButton = document.getElementById('notifications-button');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    
    if (notificationsButton && notificationsDropdown) {
        notificationsButton.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationsDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!notificationsDropdown.contains(e.target) && !notificationsButton.contains(e.target)) {
                notificationsDropdown.classList.add('hidden');
            }
        });
    }
    
    // Dark Mode Toggle
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;
    
    // Check for saved theme preference or default to 'light' mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    if (currentTheme === 'dark') {
        htmlElement.classList.add('dark');
    }
    
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            htmlElement.classList.toggle('dark');
            
            // Save preference to localStorage
            const theme = htmlElement.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
        });
    }
    
    // Dropdown Toggle Function
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        const arrow = document.getElementById(dropdownId + '-arrow');
        
        if (dropdown && arrow) {
            dropdown.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
    }
    
    // Logout Confirmation
    function confirmLogout() {
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logout-form').submit();
        }
    }
    </script>
</body>
</html>
