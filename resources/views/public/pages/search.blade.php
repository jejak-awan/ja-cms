@extends('public.layouts.app')

@section('meta_title', 'Search Results for "' . $query . '" - ' . config('app.name'))

@section('content')
<!-- Search Header -->
<section class="bg-gradient-to-r from-blue-600 to-purple-700 py-16 px-4">
    <div class="container mx-auto">
        <nav class="breadcrumb text-white/80 mb-4">
            <a href="{{ route('home') }}">Home</a>
            <span class="mx-2">/</span>
            <span class="text-white">Search</span>
        </nav>
        
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Search Results</h1>
        
        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET" class="max-w-2xl">
            <div class="relative">
                <input 
                    type="text" 
                    name="q"
                    value="{{ $query }}"
                    placeholder="Search articles, pages..." 
                    class="w-full pl-12 pr-4 py-4 rounded-lg text-gray-900 focus:outline-none focus:ring-4 focus:ring-white/30"
                    autofocus
                >
                <svg class="w-6 h-6 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </form>
        
        <p class="text-white/90 mt-4">
            Found <strong>{{ $articles->total() + $pages->total() }}</strong> results for "<strong>{{ $query }}</strong>"
        </p>
    </div>
</section>

<!-- Search Results -->
<section class="py-12 px-4 bg-gray-50">
    <div class="container mx-auto">
        <div class="max-w-5xl mx-auto">
            <!-- Articles Results -->
            @if($articles->count() > 0)
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Articles ({{ $articles->total() }})</h2>
                </div>
                
                <div class="space-y-6">
                    @foreach($articles as $article)
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="md:flex">
                            <a href="{{ route('articles.show', $article->slug) }}" class="block md:w-64 flex-shrink-0 image-overlay">
                                @if($article->featured_image)
                                    <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                         alt="{{ $article->title }}"
                                         class="w-full h-48 md:h-full object-cover"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-48 md:h-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="p-6 flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="category-badge text-xs">{{ $article->category->name ?? 'Uncategorized' }}</span>
                                    <span class="text-xs text-gray-500">Article</span>
                                </div>
                                
                                <h3 class="text-2xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition">
                                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                
                                <p class="text-gray-600 mb-4 line-clamp-2">
                                    {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 200) }}
                                </p>
                                
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $article->author->name ?? 'Admin' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $article->published_at?->format('M d, Y') ?? $article->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                
                <!-- Articles Pagination -->
                @if($articles->hasPages())
                <div class="mt-8">
                    {{ $articles->appends(['q' => $query])->links() }}
                </div>
                @endif
            </div>
            @endif
            
            <!-- Pages Results -->
            @if($pages->count() > 0)
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Pages ({{ $pages->total() }})</h2>
                </div>
                
                <div class="space-y-4">
                    @foreach($pages as $page)
                    <article class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full font-semibold">Page</span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 transition">
                                    <a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a>
                                </h3>
                                
                                <p class="text-gray-600 mb-3 line-clamp-2">
                                    {{ Str::limit(strip_tags($page->content), 200) }}
                                </p>
                                
                                <span class="text-sm text-gray-500">
                                    Updated {{ $page->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                
                <!-- Pages Pagination -->
                @if($pages->hasPages())
                <div class="mt-8">
                    {{ $pages->appends(['q' => $query])->links() }}
                </div>
                @endif
            </div>
            @endif
            
            <!-- No Results -->
            @if($articles->count() == 0 && $pages->count() == 0)
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No results found</h3>
                <p class="text-gray-600 mb-6">
                    We couldn't find any results for "<strong>{{ $query }}</strong>".<br>
                    Try different keywords or browse our categories.
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('articles.index') }}" class="btn-primary">
                        Browse Articles
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn-secondary">
                        View Categories
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
