@extends('public.layouts.app')

@section('meta_title', __('public.categories.index.title') . ' - ' . config('app.name'))
@section('meta_description', __('public.categories.index.description'))

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-blue-600 to-purple-700 py-16 px-4">
    <div class="container mx-auto">
        <nav class="breadcrumb text-white/80 mb-4">
            <a href="{{ route(app()->getLocale() . '.home') }}">@t('public.nav.home')</a>
            <span class="mx-2">/</span>
            <span class="text-white">@t('public.nav.categories')</span>
        </nav>
        
        <h1 class="text-5xl font-bold text-white mb-4">@t('public.categories.index.title')</h1>
        <p class="text-xl text-white/90">@t('public.categories.index.description')</p>
    </div>
</section>

<!-- Categories Grid -->
<section class="py-16 px-4 bg-gray-50">
    <div class="container mx-auto">
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                @foreach($categories as $category)
                <a href="{{ route(app()->getLocale() . '.categories.show', $category->slug) }}" 
                   class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2"
                   data-animate>
                    <div class="h-32 bg-gradient-to-br from-blue-500 to-purple-600 relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-6xl opacity-20">📁</div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition">
                            {{ $category->name }}
                        </h3>
                        
                        @if($category->description)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ $category->description }}
                        </p>
                        @endif
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <span class="text-sm text-gray-600">
                                {{ $category->articles_count ?? 0 }} @t('public.categories.articles_count')
                            </span>
                            <svg class="w-5 h-5 text-blue-600 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="py-16 text-center">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">@t('public.common.no_results')</h3>
                <p class="text-gray-600 mb-6">@t('public.common.no_results_description')</p>
                <a href="{{ route(app()->getLocale() . '.home') }}" class="btn-primary">@t('public.common.back_to_home')</a>
            </div>
        @endif
    </div>
</section>
@endsection
