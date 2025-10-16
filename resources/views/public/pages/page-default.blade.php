@extends('public.layouts.app')

@section('meta_title', $page->meta_title ?? $page->title_id . ' - ' . config('app.name'))
@section('meta_description', $page->meta_description ?? Str::limit(strip_tags($page->content), 160))

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-blue-600 to-purple-700 py-16 px-4">
    <div class="container mx-auto">
        <nav class="breadcrumb text-white/80 mb-4">
            <a href="{{ route(app()->getLocale() . '.home') }}">Home</a>
            <span class="mx-2">/</span>
            <span class="text-white">{{ $page->title_id }}</span>
        </nav>
        
        <h1 class="text-5xl font-bold text-white mb-4">{{ $page->title_id }}</h1>
    </div>
</section>

<!-- Page Content -->
<section class="py-16 px-4 bg-white">
    <div class="container mx-auto">
        <div class="max-w-4xl mx-auto">
            <!-- Featured Image -->
            @if($page->featured_image ?? false)
            <div class="mb-12 rounded-xl overflow-hidden shadow-lg">
                <img src="{{ asset('storage/' . $page->featured_image) }}" 
                     alt="{{ $page->title_id }}"
                     class="w-full h-auto">
            </div>
            @endif
            
            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </div>
            
            <!-- Last Updated -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Last updated: {{ $page->updated_at->format('F d, Y') }}
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
