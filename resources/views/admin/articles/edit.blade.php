@extends('admin.layouts.admin')

@section('title', __('admin.articles.edit_article'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <x-admin.page-header
        :title="__('admin.articles.edit_article')"
        :subtitle="__('admin.articles.edit_subtitle')"
    >
        <x-slot name="actions">
            <x-admin.button 
                type="link" 
                :href="route('admin.articles.index')" 
                variant="secondary"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('admin.common.back_to') }} {{ __('admin.articles.title') }}
            </x-admin.button>
        </x-slot>
    </x-admin.page-header>

    <!-- Errors -->
    @if($errors->any())
        <x-admin.alert type="error">
            <p class="font-medium">{{ __('admin.articles.errors_found') }}</p>
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-admin.alert>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.input-field
                        name="title_{{ app()->getLocale() }}"
                        :label="__('admin.articles.title_label')"
                        type="text"
                        :value="old('title_' . app()->getLocale(), $article->{'title_' . app()->getLocale()})"
                        required
                        :placeholder="__('admin.articles.title_label') . '...'"
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
                        <x-admin.input-field
                            name="slug"
                            :value="old('slug', $article->slug)"
                            placeholder="auto-generated-from-title"
                            :label="false"
                            data-original-slug="{{ $article->slug }}"
                        />
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.articles.url_slug_help') }}</p>
                </div>

                <!-- Excerpt -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.textarea-field
                        name="excerpt_{{ app()->getLocale() }}"
                        :label="__('admin.articles.excerpt_label')"
                        :value="old('excerpt_' . app()->getLocale(), $article->{'excerpt_' . app()->getLocale()})"
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
                        :value="old('content_' . app()->getLocale(), $article->{'content_' . app()->getLocale()})"
                        rows="15"
                        required
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
