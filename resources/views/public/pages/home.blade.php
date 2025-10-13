@extends('public.layouts.app')

@section('meta_title', 'Home - ' . config('app.name'))
@section('meta_description', 'Welcome to our modern CMS platform. Discover latest articles, news, and stories.')

@section('content')
<!-- Hero Section -->
<section class="hero-gradient hero-pattern py-20 px-4">
    <div class="container mx-auto text-center text-white">
        <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
            Welcome to {{ config('app.name') }}
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-gray-100 max-w-3xl mx-auto">
            Discover amazing content, stories, and insights from our community of writers
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('articles.index') }}" class="btn-primary">
                Browse Articles
            </a>
            <a href="{{ route('categories.index') }}" class="btn-secondary">
                Explore Categories
            </a>
        </div>
    </div>
</section>

<!-- Featured Articles -->
@if($featuredArticles->count() > 0)
<section class="py-16 px-4 bg-white">
    <div class="container mx-auto">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-4xl font-bold text-gray-900">Featured Articles</h2>
                <p class="text-gray-600 mt-2">Hand-picked stories for you</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredArticles as $article)
            <article class="card-hover bg-white rounded-xl shadow-lg overflow-hidden" data-animate>
                <a href="{{ route('articles.show', $article->slug) }}" class="block image-overlay">
                    @if($article->featured_image)
                        <img src="{{ str_starts_with($article->featured_image, 'http') ? $article->featured_image : asset('storage/' . $article->featured_image) }}" 
                             alt="{{ $article->title }}"
                             class="w-full h-64 object-cover"
                             loading="lazy">
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <span class="featured-badge">Featured</span>
                </a>
                
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="category-badge">{{ $article->category->name ?? 'Uncategorized' }}</span>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition">
                        <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                    </h3>
                    
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                    </p>
                    
                    <div class="article-meta">
                        <span class="flex items-center">
                            <svg class="mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $article->author->name ?? 'Admin' }}
                        </span>
                        <span class="flex items-center">
                            <svg class="mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $article->published_at?->format('M d, Y') ?? $article->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Articles -->
<section class="py-16 px-4 bg-gray-50">
    <div class="container mx-auto">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-4xl font-bold text-gray-900">Latest Articles</h2>
                <p class="text-gray-600 mt-2">Fresh content just for you</p>
            </div>
            <a href="{{ route('articles.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
                View All
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($latestArticles as $article)
            <article class="card-hover bg-white rounded-xl shadow-lg overflow-hidden" data-animate>
                <a href="{{ route('articles.show', $article->slug) }}" class="block image-overlay">
                    @if($article->featured_image)
                        <img src="{{ str_starts_with($article->featured_image, 'http') ? $article->featured_image : asset('storage/' . $article->featured_image) }}" 
                             alt="{{ $article->title }}"
                             class="w-full h-48 object-cover"
                             loading="lazy">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    @endif
                </a>
                
                <div class="p-6">
                    <span class="category-badge text-xs">{{ $article->category->name ?? 'Uncategorized' }}</span>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2 mt-3 hover:text-blue-600 transition">
                        <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                    </h3>
                    
                    <p class="text-gray-600 mb-4 line-clamp-2">
                        {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}
                    </p>
                    
                    <div class="article-meta text-xs">
                        <span>{{ $article->author->name ?? 'Admin' }}</span>
                        <span>{{ $article->published_at?->diffForHumans() ?? $article->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-16 px-4 bg-white">
    <div class="container mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-bold text-gray-900">Popular Categories</h2>
            <p class="text-gray-600 mt-2">Browse content by topic</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}" 
               class="group bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 text-center text-white hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
               data-animate>
                <div class="text-3xl mb-3">📁</div>
                <h3 class="font-bold text-lg mb-1">{{ $category->name }}</h3>
                <p class="text-sm opacity-90">{{ $category->articles_count ?? 0 }} articles</p>
            </a>
            @endforeach
        </div>
        
        <div class="text-center mt-10">
            <a href="{{ route('categories.index') }}" class="btn-primary">
                View All Categories
            </a>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-16 px-4 bg-gradient-to-r from-blue-600 to-purple-700">
    <div class="container mx-auto text-center text-white">
        <h2 class="text-4xl font-bold mb-4">Stay Updated</h2>
        <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">
            Subscribe to our newsletter and never miss our latest articles and updates
        </p>
        
        <form data-newsletter-form class="max-w-md mx-auto">
            @csrf
            <div class="flex flex-col sm:flex-row gap-3">
                <input 
                    type="email" 
                    placeholder="Enter your email" 
                    required
                    class="flex-1 px-6 py-4 rounded-lg text-gray-900 focus:outline-none focus:ring-4 focus:ring-white/30"
                >
                <button type="submit" class="px-8 py-4 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 transition">
                    Subscribe
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
