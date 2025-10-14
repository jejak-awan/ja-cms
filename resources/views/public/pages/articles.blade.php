@extends('public.layouts.app')

@section('meta_title', 'Articles - ' . config('app.name'))
@section('meta_description', 'Browse our collection of articles on various topics')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-blue-600 to-purple-700 py-16 px-4">
    <div class="container mx-auto">
        <nav class="breadcrumb text-white/80 mb-4">
            <a href="{{ route(app()->getLocale() . '.home') }}">Home</a>
            <span class="mx-2">/</span>
            <span class="text-white">Articles</span>
        </nav>
        
        <h1 class="text-5xl font-bold text-white mb-4">All Articles</h1>
        <p class="text-xl text-white/90">Discover stories, thinking, and expertise from writers on any topic</p>
    </div>
</section>

<!-- Filters & Search -->
<section class="bg-white border-b py-6 px-4 sticky top-16 z-40 shadow-sm">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <!-- Search Form -->
            <form action="{{ route(app()->getLocale() . '.articles.index') }}" method="GET" class="flex-1 max-w-md">
                <div class="relative">
                    <input 
                        type="text" 
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search articles..." 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    >
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </form>
            
            <!-- Category Filter -->
            <div class="flex items-center gap-3 overflow-x-auto pb-2 md:pb-0">
                <a href="{{ route(app()->getLocale() . '.articles.index') }}" 
                   class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All
                </a>
                @foreach($categories as $cat)
                <a href="{{ route(app()->getLocale() . '.articles.index', ['category' => $cat->slug]) }}" 
                   class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition {{ request('category') == $cat->slug ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $cat->name }} ({{ $cat->articles_count }})
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Articles Grid -->
<section class="py-12 px-4 bg-gray-50">
    <div class="container mx-auto">
        @if($articles->count() > 0)
            <!-- Results Info -->
            <div class="mb-8 flex items-center justify-between">
                <p class="text-gray-600">
                    Showing {{ $articles->firstItem() }} - {{ $articles->lastItem() }} of {{ $articles->total() }} articles
                </p>
            </div>
            
            <!-- Articles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($articles as $article)
                <article class="card-hover bg-white rounded-xl shadow-lg overflow-hidden" data-animate>
                    <a href="{{ route(app()->getLocale() . '.articles.show', $article->slug) }}" class="block image-overlay">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                 alt="{{ $article->title }}"
                                 class="w-full h-56 object-cover"
                                 loading="lazy">
                        @else
                            <div class="w-full h-56 bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        @if($article->featured ?? false)
                            <span class="featured-badge">Featured</span>
                        @endif
                    </a>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <a href="{{ route(app()->getLocale() . '.categories.show', $article->category->slug) }}" class="category-badge hover:bg-blue-700 transition">
                                {{ $article->category->name ?? 'Uncategorized' }}
                            </a>
                            
                            @if($article->views ?? 0 > 0)
                            <span class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ number_format($article->views) }}
                            </span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition">
                            <a href="{{ route(app()->getLocale() . '.articles.show', $article->slug) }}">{{ $article->title }}</a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($article->author->name ?? 'A', 0, 1) }}
                                </div>
                                <span class="text-sm text-gray-700 font-medium">{{ $article->author->name ?? 'Admin' }}</span>
                            </div>
                            
                            <span class="text-sm text-gray-500">
                                {{ $article->published_at?->diffForHumans() ?? $article->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $articles->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No articles found</h3>
                <p class="text-gray-600 mb-6">
                    @if(request('q'))
                        No articles match your search "{{ request('q') }}"
                    @elseif(request('category'))
                        No articles in this category yet
                    @else
                        There are no published articles yet
                    @endif
                </p>
                <a href="{{ route(app()->getLocale() . '.home') }}" class="btn-primary">
                    Back to Home
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
