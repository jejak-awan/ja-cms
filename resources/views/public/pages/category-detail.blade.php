@extends('public.layouts.app')

@section('meta_title', $category->name . ' - Categories - ' . config('app.name'))
@section('meta_description', $category->description ?? 'Browse articles in ' . $category->name)

@section('content')
<!-- Category Header -->
<section class="bg-gradient-to-r from-blue-600 to-purple-700 py-16 px-4">
    <div class="container mx-auto">
        <nav class="breadcrumb text-white/80 mb-4">
            <a href="{{ route(app()->getLocale() . '.home') }}">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route(app()->getLocale() . '.categories.index') }}">Categories</a>
            <span class="mx-2">/</span>
            <span class="text-white">{{ $category->name }}</span>
        </nav>
        
        <div class="flex items-start gap-6">
            <div class="w-20 h-20 bg-white/20 rounded-xl flex items-center justify-center text-5xl backdrop-blur-sm">
                📁
            </div>
            <div class="flex-1">
                <h1 class="text-5xl font-bold text-white mb-4">{{ $category->name }}</h1>
                @if($category->description)
                <p class="text-xl text-white/90 mb-4">{{ $category->description }}</p>
                @endif
                <p class="text-white/80">
                    <span class="font-semibold">{{ $category->articles_count }}</span> articles in this category
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Articles in Category -->
<section class="py-12 px-4 bg-gray-50">
    <div class="container mx-auto">
        @if($articles->count() > 0)
            <!-- Results Info -->
            <div class="mb-8">
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
                                 alt="{{ $article->title_id }}"
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
                        @if($article->views ?? 0 > 0)
                        <div class="flex justify-end mb-2">
                            <span class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ number_format($article->views) }}
                            </span>
                        </div>
                        @endif
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition">
                            <a href="{{ route(app()->getLocale() . '.articles.show', $article->slug) }}">{{ $article->title_id }}</a>
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
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No articles yet</h3>
                <p class="text-gray-600 mb-6">There are no published articles in this category</p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route(app()->getLocale() . '.categories.index') }}" class="btn-secondary">
                        Browse Categories
                    </a>
                    <a href="{{ route(app()->getLocale() . '.articles.index') }}" class="btn-primary">
                        View All Articles
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Other Categories -->
<section class="py-12 px-4 bg-white">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Explore Other Categories</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $otherCategories = \App\Modules\Category\Models\Category::where('id', '!=', $category->id)
                    ->withCount('articles')
                    ->orderBy('name')
                    ->take(6)
                    ->get();
            @endphp
            
            @foreach($otherCategories as $cat)
            <a href="{{ route(app()->getLocale() . '.categories.show', $cat->slug) }}" 
               class="group bg-gradient-to-br from-gray-100 to-gray-200 hover:from-blue-500 hover:to-purple-600 rounded-xl p-6 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📁</div>
                <h3 class="font-bold text-gray-900 group-hover:text-white transition mb-1">{{ $cat->name }}</h3>
                <p class="text-xs text-gray-600 group-hover:text-white/80 transition">{{ $cat->articles_count }} articles</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
