@extends('admin.layouts.admin')

@section('title', __('admin.pages.create'))

@section('content')
<div class="space-y-6">
    <x-admin.page-header
        :title="__('admin.pages.create_new')"
        :subtitle="__('admin.pages.create_subtitle')"
    >
        <x-slot name="actions">
            <x-admin.button 
                type="link" 
                :href="route('admin.pages.index')" 
                variant="secondary"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('admin.pages.back_to_pages') }}
            </x-admin.button>
        </x-slot>
    </x-admin.page-header>

    @if($errors->any())
        <x-admin.alert type="error">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-admin.alert>
    @endif

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.input-field
                        name="title_{{ app()->getLocale() }}"
                        :label="__('admin.pages.title_label')"
                        type="text"
                        :value="old('title_' . app()->getLocale())"
                        required
                        :placeholder="__('admin.pages.title_label') . '...'"
                        class="text-lg"
                        onkeyup="generateSlug()"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.input-field
                        name="slug"
                        :label="__('admin.pages.url_slug')"
                        :value="old('slug')"
                        :placeholder="__('admin.pages.url_slug_help')"
                        :help="__('admin.pages.url_slug_help')"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.textarea-field
                        name="excerpt"
                        :label="__('admin.pages.excerpt')"
                        :value="old('excerpt')"
                        rows="3"
                        :placeholder="__('admin.pages.excerpt_placeholder')"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.textarea-field
                        name="content_{{ app()->getLocale() }}"
                        :label="__('admin.pages.content_label')"
                        :value="old('content_' . app()->getLocale())"
                        rows="15"
                        required
                        :placeholder="__('admin.pages.content_placeholder')"
                    />
                </div>

                @include('admin.pages.includes.seo-section')
            </div>

            <div class="space-y-6">
                @include('admin.pages.includes.publish-settings')

                @include('admin.pages.includes.template-selection', ['templates' => $templates])

                @include('admin.pages.includes.featured-image')
            </div>
        </div>
    </form>
</div>

@push('scripts')
    @include('admin.pages.includes.ckeditor-init')

    @include('admin.pages.includes.slug-generator')

    @include('admin.pages.includes.image-preview')

@endpush
@endsection
