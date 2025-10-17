<!DOCTYPE html>
<html lang="en" data-theme="default">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Laravel CMS</title>
    
    {{-- Base Tailwind CSS --}}
    @vite('resources/css/app.css')
    
    {{-- Page Transitions CSS --}}
    <link rel="stylesheet" href="{{ asset('css/page-transitions.css') }}">
    
    {{-- CKEditor Custom Styles --}}
    <style>
        /* CKEditor 5 Custom Styling - Better proportions & Resizable */
        .ck-editor { width: 100%; margin: 0; }
        
        .ck-editor__editable {
            min-height: 400px !important;
            max-height: 800px !important;
            overflow-y: auto !important;
            resize: vertical !important;
            transition: min-height 0.2s ease;
        }
        
        .ck-editor__editable .ck-content {
            padding: 1.5rem !important;
            line-height: 1.8 !important;
            font-size: 16px !important;
        }
        
        .ck-toolbar {
            border-radius: 6px 6px 0 0 !important;
            border-color: #d1d5db !important;
            background: #f9fafb !important;
            padding: 8px 12px !important;
        }
        
        .ck-editor__main {
            border: 1px solid #d1d5db !important;
            border-radius: 0 0 6px 6px !important;
            background: white !important;
        }
        
        .ck-editor__editable:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
        
        .ck-editor__editable { scroll-behavior: smooth; }
        
        .ck-editor__editable img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 4px;
            margin: 1rem 0;
        }
        
        .ck-editor__editable table {
            border-collapse: collapse !important;
            width: 100% !important;
            margin: 1rem 0 !important;
        }
        
        .ck-editor__editable table td,
        .ck-editor__editable table th {
            border: 1px solid #d1d5db !important;
            padding: 0.5rem !important;
        }
        
        .ck-editor__editable pre {
            background: #f3f4f6 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 4px !important;
            padding: 1rem !important;
            overflow-x: auto !important;
            margin: 1rem 0 !important;
        }
        
        .ck-editor__editable blockquote {
            border-left: 4px solid #3b82f6 !important;
            padding-left: 1rem !important;
            margin: 1rem 0 !important;
            color: #6b7280 !important;
            font-style: italic !important;
        }
        
        .ck-editor__editable a {
            color: #3b82f6 !important;
            text-decoration: underline !important;
        }
        
        .ck-editor__editable a:hover { color: #2563eb !important; }
        
        @media (max-width: 768px) {
            .ck-editor__editable { min-height: 300px !important; }
            .ck-editor__editable .ck-content {
                padding: 1rem !important;
                font-size: 14px !important;
            }
            .ck-toolbar { padding: 6px 8px !important; }
        }
        
        @media (prefers-color-scheme: dark) {
            .ck-toolbar {
                background: #1f2937 !important;
                border-color: #374151 !important;
            }
            .ck-editor__editable {
                background: #111827 !important;
                color: #f9fafb !important;
            }
            .ck-editor__main {
                background: #111827 !important;
                border-color: #374151 !important;
            }
        }
    </style>
    
    {{-- Theme-specific CSS (if exists) --}}
    @if(file_exists(public_path('themes/admin/default/css/style.css')))
        <link rel="stylesheet" href="{{ asset('themes/admin/default/css/style.css') }}">
    @endif
    
    @stack('styles')
    
    {{-- Admin Layout Styles --}}
    @include('admin.layouts.admin-styles')
</head>
<body class="bg-gray-50 font-sans antialiased theme-default">
    {{-- Mobile Menu Overlay --}}
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
    
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar with Navigation --}}
        <x-admin.sidebar>
            @include('admin.layouts.partials.sidebar-nav')
        </x-admin.sidebar>

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Admin Header --}}
            @include('admin.layouts.partials.admin-header')

            {{-- Page Content --}}
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

            {{-- Footer --}}
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-3 px-4 md:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between text-xs md:text-sm text-gray-600 dark:text-gray-400 space-y-2 md:space-y-0">
                    <span>&copy; {{ date('Y') }} Laravel CMS. All rights reserved.</span>
                    <span>Version 1.0.0</span>
                </div>
            </footer>
        </div>
    </div>

    <div id="app"></div>
    
    {{-- Keyboard Shortcuts Modal --}}
    <x-keyboard-shortcuts />
    
    {{-- Base Vue/JS --}}
    @vite(['resources/js/app.js', 'resources/js/activity-feed.js'])
    
    {{-- Theme-specific JS (if exists) --}}
    @if(file_exists(public_path('themes/admin/default/js/script.js')))
        <script src="{{ asset('themes/admin/default/js/script.js') }}"></script>
    @endif
    
    @stack('scripts')
    
    {{-- Admin Layout Scripts --}}
    @include('admin.layouts.partials.layout-scripts')
</body>
</html>
