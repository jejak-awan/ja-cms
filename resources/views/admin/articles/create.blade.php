@extends('admin.layouts.admin')

@section('title', __('admin.articles.create'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.articles.create_new') }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('admin.articles.create_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded-lg font-medium transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('admin.common.back_to') }} {{ __('admin.articles.title') }}
        </a>
    </div>

    <!-- Errors -->
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-200">
        <div class="flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-medium">{{ __('admin.articles.errors_found') }}</p>
                <ul class="mt-1 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.input-field
                        name="title_{{ app()->getLocale() }}"
                        :label="__('admin.articles.title_label')"
                        type="text"
                        :value="old('title_' . app()->getLocale())"
                        required
                        placeholder="{{ __('admin.articles.title_label') }}..."
                        class="text-lg"
                        onkeyup="generateSlug()"
                    />
                </div>

                <!-- Slug -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('admin.articles.url_slug') }}
                    </label>
                    <div class="flex items-center">
                        <span class="text-gray-500 dark:text-gray-400 text-sm mr-2">{{ url('/') }}/</span>
                        <input 
                            type="text" 
                            name="slug" 
                            id="slug" 
                            value="{{ old('slug') }}" 
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                            placeholder="auto-generated-from-title"
                        >
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.articles.url_slug_help') }}</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.textarea-field
                        name="excerpt_{{ app()->getLocale() }}"
                        :label="__('admin.articles.excerpt_label')"
                        :value="old('excerpt_' . app()->getLocale())"
                        rows="3"
                        :placeholder="__('admin.articles.excerpt_label') . ' (optional)'"
                        :help="__('admin.articles.excerpt_help')"
                    />
                </div>

                <!-- Content Editor -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.textarea-field
                        name="content_{{ app()->getLocale() }}"
                        :label="__('admin.articles.content_label')"
                        :value="old('content_' . app()->getLocale())"
                        rows="15"
                    />
                </div>

                <!-- SEO Section -->
                @include('admin.articles.includes.seo-section')
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish Settings -->
                @include('admin.articles.includes.publish-settings')

                <!-- Category -->
                @include('admin.articles.includes.category-selection', ['categories' => $categories])

                <!-- Enhanced Tags Section -->
                @include('admin.articles.includes.enhanced-tags', ['tags' => $tags])

                <!-- Featured Image -->
                @include('admin.articles.includes.featured-image')
            </div>
        </div>
        
        <!-- Hidden inputs for new tags -->
        <div id="new_tags_inputs">
            <!-- New tags will be added here dynamically -->
        </div>
    </form>
</div>

@push('scripts')
    @include('admin.articles.includes.ckeditor-init')
    @include('admin.articles.includes.slug-generator')
    @include('admin.articles.includes.auto-update-seo')
    @include('admin.articles.includes.enhanced-tags-script')
    @include('admin.articles.includes.image-preview')
@endpush
@endsection
