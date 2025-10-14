@extends('public.layouts.app')

@section('meta_title', $article->meta_title ?? $article->title . ' - ' . config('app.name'))
@section('meta_description', $article->meta_description ?? $article->excerpt ?? Str::limit(strip_tags($article->content), 160))
@section('meta_keywords', $article->meta_keywords ?? '')

@section('og_title', $article->title)
@section('og_description', $article->excerpt ?? Str::limit(strip_tags($article->content), 200))
@section('og_image', $article->featured_image ? asset('storage/' . $article->featured_image) : asset('images/og-default.jpg'))
@section('og_type', 'article')

@section('content')
<!-- Article Header -->
<article class="bg-white" data-article-id="{{ $article->id }}">
    <header class="relative">
        <!-- Featured Image -->
        @if($article->featured_image)
        <div class="w-full h-96 md:h-[500px] overflow-hidden">
            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                 alt="{{ $article->title }}"
                 class="w-full h-full object-cover">
        </div>
        @else
        <div class="w-full h-64 bg-gradient-to-br from-blue-500 to-purple-600"></div>
        @endif
        
        <!-- Header Content Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex items-end">
            <div class="container mx-auto px-4 pb-12">
                <!-- Breadcrumb -->
                <nav class="breadcrumb text-white/90 mb-4">
                    <a href="{{ route(app()->getLocale() . '.home') }}" class="hover:text-white">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route(app()->getLocale() . '.articles.index') }}" class="hover:text-white">Articles</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route(app()->getLocale() . '.categories.show', $article->category->slug) }}" class="hover:text-white">
                        {{ $article->category->name }}
                    </a>
                </nav>
                
                <!-- Category Badge -->
                <a href="{{ route(app()->getLocale() . '.categories.show', $article->category->slug) }}" class="category-badge inline-block mb-4">
                    {{ $article->category->name ?? 'Uncategorized' }}
                </a>
                
                <!-- Title -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 max-w-4xl">
                    {{ $article->title }}
                </h1>
                
                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-6 text-white/90">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold backdrop-blur-sm">
                            {{ substr($article->author->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $article->author->name ?? 'Admin' }}</p>
                            <p class="text-sm text-white/70">Author</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $article->published_at?->format('F d, Y') ?? $article->created_at->format('F d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min read</span>
                    </div>
                    
                    @if($article->views ?? 0 > 0)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>{{ number_format($article->views) }} views</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </header>
    
    <!-- Article Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Excerpt -->
            @if($article->excerpt)
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-lg mb-8">
                <p class="text-lg text-gray-700 leading-relaxed font-medium">
                    {{ $article->excerpt }}
                </p>
            </div>
            @endif
            
            <!-- Content -->
            <div class="prose prose-lg max-w-none mb-12">
                {!! $article->content !!}
            </div>
            
            <!-- Tags (if implemented later) -->
            <div class="border-t border-b border-gray-200 py-6 mb-8">
                <div class="flex flex-wrap gap-2">
                    <span class="text-gray-600 font-semibold">Tags:</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $article->category->name }}</span>
                    <!-- Add more tags here when tag system is implemented -->
                </div>
            </div>
            
            <!-- Share Buttons -->
            <div class="bg-gray-50 rounded-xl p-6 mb-12">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Share this article</h3>
                <div class="flex flex-wrap gap-3">
                    <button data-share 
                            data-title="{{ $article->title }}"
                            data-url="{{ route(app()->getLocale() . '.articles.show', $article->slug) }}"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                        </svg>
                        Share
                    </button>
                    
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route(app()->getLocale() . '.articles.show', $article->slug)) }}" 
                       target="_blank"
                       class="flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                        Twitter
                    </a>
                    
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route(app()->getLocale() . '.articles.show', $article->slug)) }}" 
                       target="_blank"
                       class="flex items-center gap-2 px-4 py-2 bg-blue-800 text-white rounded-lg hover:bg-blue-900 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                        </svg>
                        Facebook
                    </a>
                </div>
            </div>
            
            <!-- Author Info -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-8 mb-12">
                <div class="flex items-start gap-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                        {{ substr($article->author->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $article->author->name ?? 'Admin' }}</h3>
                        <p class="text-gray-600 mb-4">Content Writer & Editor</p>
                        <p class="text-gray-700">
                            Passionate about creating engaging content that informs and inspires readers.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Related Articles -->
@if($relatedArticles->count() > 0)
<section class="bg-gray-50 py-16 px-4">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Related Articles</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($relatedArticles as $related)
            <article class="card-hover bg-white rounded-xl shadow-lg overflow-hidden">
                <a href="{{ route(app()->getLocale() . '.articles.show', $related->slug) }}" class="block image-overlay">
                    @if($related->featured_image)
                        <img src="{{ asset('storage/' . $related->featured_image) }}" 
                             alt="{{ $related->title }}"
                             class="w-full h-48 object-cover"
                             loading="lazy">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-gray-400 to-gray-600"></div>
                    @endif
                </a>
                
                <div class="p-6">
                    <span class="category-badge text-xs">{{ $related->category->name }}</span>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-2 mt-3 hover:text-blue-600 transition">
                        <a href="{{ route(app()->getLocale() . '.articles.show', $related->slug) }}">{{ $related->title }}</a>
                    </h3>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ $related->excerpt ?? Str::limit(strip_tags($related->content), 100) }}
                    </p>
                    
                    <span class="text-sm text-gray-500">
                        {{ $related->published_at?->diffForHumans() ?? $related->created_at->diffForHumans() }}
                    </span>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
