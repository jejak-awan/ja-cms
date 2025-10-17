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
                    @php
                        $locale = app()->getLocale();
                        $titleField = 'title_' . $locale;
                    @endphp
                    <x-admin.input-field
                        :name="$titleField"
                        :label="__('admin.articles.title_label')"
                        type="text"
                        :value="old($titleField)"
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
                    @php
                        $excerptField = 'excerpt_' . $locale;
                    @endphp
                    <x-admin.textarea-field
                        :name="$excerptField"
                        :label="__('admin.articles.excerpt_label')"
                        :value="old($excerptField)"
                        rows="3"
                        :placeholder="__('admin.articles.excerpt_label') . ' (optional)'"
                        :help="__('admin.articles.excerpt_help')"
                    />
                </div>

                <!-- Content Editor -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    @php
                        $contentField = 'content_' . $locale;
                    @endphp
                    <x-admin.textarea-field
                        :name="$contentField"
                        :label="__('admin.articles.content_label')"
                        :value="old($contentField)"
                        rows="15"
                    />
                </div>

                <!-- SEO Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        {{ __('admin.articles.seo_settings') }}
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('admin.articles.meta_title') }}
                            </label>
                            <input 
                                type="text" 
                                name="meta_title" 
                                id="meta_title" 
                                value="{{ old('meta_title') }}" 
                                maxlength="60"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="{{ __('admin.articles.meta_title') }}"
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.articles.meta_title_help') }}</p>
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('admin.articles.meta_description') }}
                            </label>
                            <textarea 
                                name="meta_description" 
                                id="meta_description" 
                                rows="3"
                                maxlength="160"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="{{ __('admin.articles.meta_description') }}"
                            >{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.articles.meta_description_help') }}</p>
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('admin.articles.meta_keywords') }}
                            </label>
                            <input 
                                type="text" 
                                name="meta_keywords" 
                                id="meta_keywords" 
                                value="{{ old('meta_keywords') }}" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="{{ __('admin.articles.meta_keywords') }}"
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.articles.meta_keywords_help') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish Settings -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.articles.publish_settings') }}</h3>
                    
                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.common.status') }} <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="status" 
                            id="status" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('admin.common.draft') }}</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>{{ __('admin.common.published') }}</option>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>{{ __('admin.articles.schedule') }}</option>
                        </select>
                    </div>

                    <!-- Publish Date -->
                    <div class="mb-4">
                        <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('admin.articles.publish_date') }}
                        </label>
                        <input 
                            type="datetime-local" 
                            name="published_at" 
                            id="published_at" 
                            value="{{ old('published_at') }}" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.articles.publish_immediately') }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col space-y-2 pt-4 border-t dark:border-gray-700">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                            {{ __('admin.articles.create') }}
                        </button>
                        <a href="{{ route('admin.articles.index') }}" class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded-lg font-medium transition text-center">
                            {{ __('admin.common.cancel') }}
                        </a>
                    </div>
                </div>

                <!-- Category -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.articles.category_label') }}</h3>
                    <select 
                        name="category_id" 
                        id="category_id" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
                    >
                        <option value="">{{ __('admin.articles.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.articles.tags_label') }}</h3>
                    <div class="space-y-2">
                        @foreach($tags as $tag)
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="tags[]" 
                                    value="{{ $tag->id }}" 
                                    {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                    class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                                >
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @if($tags->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.articles.no_tags') }}</p>
                    @endif
                </div>

                <!-- Featured Image -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.articles.featured_image') }}</h3>
                    
                    <div class="space-y-3">
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center">
                            <input 
                                type="file" 
                                name="featured_image" 
                                id="featured_image" 
                                accept="image/*"
                                class="hidden"
                                onchange="previewImage(this)"
                            >
                            <label for="featured_image" class="cursor-pointer">
                                <div id="imagePreview" class="hidden mb-3">
                                    <img id="preview" src="" alt="Preview" class="max-w-full h-auto rounded">
                                </div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium text-blue-600 hover:text-blue-500">{{ __('admin.articles.upload_image') }}</span>
                                    {{ __('admin.articles.drag_and_drop') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, GIF up to 2MB</p>
                            </label>
                        </div>
                    </div>
                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Initialize CKEditor (loaded from local via Vite)
document.addEventListener('DOMContentLoaded', async function() {
    console.log('Initializing CKEditor...');
    
    if (typeof window.initCKEditor === 'function') {
        // Initialize content editor dynamically based on locale
        const locale = '{{ app()->getLocale() }}';
        const contentField = `content_${locale}`;
        const textarea = document.querySelector(`[name="${contentField}"]`);
        
        if (textarea) {
            try {
                await window.initCKEditor(textarea, {
                    placeholder: 'Start writing your article content...',
                    uploadUrl: '{{ route("admin.media.upload.image") }}'
                });
                console.log(`✓ CKEditor initialized for ${locale}`);
            } catch (error) {
                console.error('✗ CKEditor initialization failed:', error);
            }
        }
    } else {
        console.error('initCKEditor function not found. Check if app.js is loaded.');
    }
});

// Slug generator - dynamic based on locale
function generateSlug() {
    const locale = '{{ app()->getLocale() }}';
    const titleField = `title_${locale}`;
    const titleInput = document.querySelector(`[name="${titleField}"]`);
    
    if (!titleInput) return;
    
    const title = titleInput.value;
    const slug = title
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById('slug').value = slug;
}

// Image preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
