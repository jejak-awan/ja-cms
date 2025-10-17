<!DOCTYPE html>
<html lang="@locale" dir="@localeDir">
<head>
        <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('description', 'Laravel CMS with multilingual support')">
    <meta name="keywords" content="@yield('keywords', 'laravel, cms, multilingual')">
    <meta name="author" content="{{ config('app.name') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('description', 'Laravel CMS with multilingual support')">
    <meta property="og:image" content="@yield('og_image', asset('images/default-og.jpg'))">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name'))">
    <meta property="twitter:description" content="@yield('description', 'Laravel CMS with multilingual support')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/default-og.jpg'))">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Fonts -->
    {{-- Fonts loaded from local in app.css --}}
    
    <!-- Styles -->
    @vite('resources/css/app.css')
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navbar -->
    <nav class="navbar fixed w-full top-0 z-50 transition-all duration-300 bg-white shadow-md no-print">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">L</span>
                        </div>
                        <span class="text-xl font-bold text-gradient">{{ config('app.name', 'Laravel CMS') }}</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-blue-600 font-medium transition">@t('messages.home')</a>
                    <a href="/articles" class="text-gray-700 hover:text-blue-600 font-medium transition">@t('admin.nav.articles')</a>
                    <a href="/categories" class="text-gray-700 hover:text-blue-600 font-medium transition">@t('admin.nav.categories')</a>
                    <a href="/about" class="text-gray-700 hover:text-blue-600 font-medium transition">@t('messages.about')</a>
                    <a href="/contact" class="text-gray-700 hover:text-blue-600 font-medium transition">@t('messages.contact')</a>
                </div>
                
                <!-- Search, Language & Login -->
                <div class="hidden md:flex items-center space-x-4">
                    <button data-search-toggle class="p-2 text-gray-600 hover:text-blue-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    
                    <!-- Language Switcher -->
                    @if(count(active_languages()) > 1)
                        <x-language-switcher size="sm" :showLabel="false" />
                    @endif
                    
                    @auth
                        <a href="/admin" class="btn-primary text-sm py-2 px-4">@t('admin.nav.dashboard')</a>
                    @else
                        <a href="/login" class="text-gray-700 hover:text-blue-600 font-medium transition">@t('auth.login')</a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button data-mobile-menu-button class="md:hidden p-2 text-gray-600" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div data-mobile-menu class="hidden md:hidden py-4 border-t border-gray-200">
                <div class="flex flex-col space-y-3">
                    <a href="/" class="text-gray-700 hover:text-blue-600 font-medium transition py-2">@t('messages.home')</a>
                    <a href="/articles" class="text-gray-700 hover:text-blue-600 font-medium transition py-2">@t('admin.nav.articles')</a>
                    <a href="/categories" class="text-gray-700 hover:text-blue-600 font-medium transition py-2">@t('admin.nav.categories')</a>
                    <a href="/about" class="text-gray-700 hover:text-blue-600 font-medium transition py-2">@t('messages.about')</a>
                    <a href="/contact" class="text-gray-700 hover:text-blue-600 font-medium transition py-2">@t('messages.contact')</a>
                    
                    <!-- Language Switcher for Mobile -->
                    @if(count(active_languages()) > 1)
                        <div class="py-2">
                            <x-language-switcher size="sm" :showLabel="true" position="bottom-left" />
                        </div>
                    @endif
                    
                    @auth
                        <a href="/admin" class="text-blue-600 font-medium py-2">@t('admin.nav.dashboard')</a>
                    @else
                        <a href="/login" class="text-gray-700 hover:text-blue-600 font-medium transition py-2">@t('auth.login')</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8 no-print">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ config('app.name', 'Laravel CMS') }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Modern and powerful content management system built with Laravel. Create, manage, and publish your content with ease.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold mb-4">@t('messages.quick_links')</h3>
                    <ul class="footer-links space-y-2 text-sm">
                        <li><a href="/">@t('messages.home')</a></li>
                        <li><a href="/articles">@t('admin.nav.articles')</a></li>
                        <li><a href="/categories">@t('admin.nav.categories')</a></li>
                        <li><a href="/about">@t('messages.about')</a></li>
                    </ul>
                </div>
                
                <!-- Categories -->
                <div>
                    <h3 class="text-lg font-bold mb-4">@t('admin.nav.categories')</h3>
                    <ul class="footer-links space-y-2 text-sm">
                        <li><a href="/categories/technology">@t('messages.categories.technology')</a></li>
                        <li><a href="/categories/lifestyle">@t('messages.categories.lifestyle')</a></li>
                        <li><a href="/categories/business">@t('messages.categories.business')</a></li>
                        <li><a href="/categories/travel">@t('messages.categories.travel')</a></li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h3 class="text-lg font-bold mb-4">@t('messages.newsletter.title')</h3>
                    <p class="text-gray-400 text-sm mb-4">@t('messages.newsletter.description')</p>
                    <form data-newsletter-form class="space-y-2">
                        @csrf
                        <input 
                            type="email" 
                            placeholder="@t('messages.newsletter.email_placeholder')" 
                            required
                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm focus:outline-none focus:border-blue-500 transition"
                        >
                        <button type="submit" class="w-full btn-primary text-sm py-2">
                            @t('messages.newsletter.subscribe')
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Social & Copyright -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                    
                    <!-- Social Icons -->
                    <div class="flex items-center space-x-3">
                        <a href="#" class="social-icon">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Search Modal -->
    <div data-search-modal class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-start justify-center pt-20">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Search</h3>
                    <button data-search-close class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="/search" method="GET">
                    <input 
                        type="text" 
                        name="q"
                        data-search-input
                        placeholder="Search articles, pages..." 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        autofocus
                    >
                </form>
            </div>
        </div>
    </div>
    
    <!-- Back to Top Button -->
    <button 
        data-back-to-top 
        class="hidden fixed bottom-8 right-8 p-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition no-print"
        aria-label="Back to top"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
    
    <!-- Base Scripts -->
    @vite('resources/js/app.js')
    
    <!-- Theme-specific JS -->
    @if(file_exists(public_path('themes/public/' . active_theme() . '/js/script.js')))
        <script src="{{ asset('themes/public/' . active_theme() . '/js/script.js') }}"></script>
    @endif
    
    @stack('scripts')
</body>
</html>
