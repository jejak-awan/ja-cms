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
                        name="title_id"
                        :label="__('admin.articles.title_label')"
                        type="text"
                        :value="old('title_id', $article->title)"
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
                        />
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.articles.url_slug_help') }}</p>
                </div>

                <!-- Excerpt -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.textarea-field
                        name="excerpt_id"
                        :label="__('admin.articles.excerpt_label')"
                        :value="old('excerpt_id', $article->excerpt)"
                        rows="3"
                        :placeholder="__('admin.articles.excerpt_label') . ' (optional)'"
                        :help="__('admin.articles.excerpt_help')"
                    />
                </div>

                <!-- Content Editor -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.textarea-field
                        name="content_id"
                        :label="__('admin.articles.content_label')"
                        :value="old('content_id', $article->content)"
                        rows="15"
                        required
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
                        <x-admin.input-field
                            name="meta_title"
                            :label="__('admin.articles.meta_title')"
                            :value="old('meta_title', $article->meta_title)"
                            maxlength="60"
                            :placeholder="__('admin.articles.meta_title')"
                            :help="__('admin.articles.meta_title_help')"
                        />

                        <!-- Meta Description -->
                        <x-admin.textarea-field
                            name="meta_description"
                            :label="__('admin.articles.meta_description')"
                            :value="old('meta_description', $article->meta_description)"
                            rows="3"
                            maxlength="160"
                            :placeholder="__('admin.articles.meta_description')"
                            :help="__('admin.articles.meta_description_help')"
                        />

                        <!-- Meta Keywords -->
                        <x-admin.input-field
                            name="meta_keywords"
                            :label="__('admin.articles.meta_keywords')"
                            :value="old('meta_keywords', $article->meta_keywords)"
                            :placeholder="__('admin.articles.meta_keywords')"
                            :help="__('admin.articles.meta_keywords_help')"
                        />
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
                        <x-admin.select-field
                            name="status"
                            :label="__('admin.common.status')"
                            required
                            :options="[
                                'draft' => __('admin.common.draft'),
                                'published' => __('admin.common.published'),
                                'scheduled' => __('admin.articles.schedule')
                            ]"
                            :selected="old('status', $article->status)"
                        />
                    </div>

                    <!-- Publish Date -->
                    <div class="mb-4">
                        <x-admin.input-field
                            name="published_at"
                            :label="__('admin.articles.publish_date')"
                            type="datetime-local"
                            :value="old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '')"
                            :help="__('admin.articles.publish_immediately')"
                        />
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col space-y-2 pt-4 border-t dark:border-gray-700">
                        <x-admin.button type="submit" variant="primary" class="w-full">
                            {{ __('admin.articles.update') }}
                        </x-admin.button>
                        <x-admin.button 
                            type="link" 
                            :href="route('admin.articles.index')" 
                            variant="secondary" 
                            class="w-full text-center"
                        >
                            {{ __('admin.common.cancel') }}
                        </x-admin.button>
                    </div>
                </div>

                <!-- Category -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.articles.category_label') }}</h3>
                    <x-admin.select-field
                        name="category_id"
                        :label="false"
                        required
                        :options="['' => __('admin.articles.select_category')] + $categories->pluck('name', 'id')->toArray()"
                        :selected="old('category_id', $article->category_id)"
                    />
                </div>

                <!-- Tags -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.articles.tags_label') }}</h3>
                    <div class="space-y-2">
                        @foreach($tags as $tag)
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="tags[]" 
                                    value="{{ $tag->id }}" 
                                    {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                    class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700"
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
                        @if($article->featured_image)
                        <div class="mb-3">
                            <img src="{{ Storage::url($article->featured_image) }}" alt="Current image" class="w-full h-auto rounded">
                            <p class="text-xs text-gray-500 mt-2">Current image</p>
                        </div>
                        @endif

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
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
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
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
        const textarea = document.querySelector('[name="content_id"]');
        
        if (textarea) {
            try {
                await window.initCKEditor(textarea, {
                    placeholder: 'Start writing your article content here...',
                    uploadUrl: '{{ route("admin.media.upload.image") }}'
                });
                console.log('✓ CKEditor initialized');
            } catch (error) {
                console.error('✗ CKEditor initialization failed:', error);
            }
        }
    } else {
        console.error('initCKEditor function not found. Check if app.js is loaded.');
    }
});

// Auto-generate Slug, Meta Title, and Meta Description
function generateSlug() {
    const title = document.getElementById('title_id').value;
    const excerpt = document.getElementById('excerpt_id') ? document.getElementById('excerpt_id').value : '';
    
    // Generate slug
    const slug = title
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    
    const slugField = document.getElementById('slug');
    if (slugField) {
        slugField.value = slug;
    }
    
    // Auto-generate meta title (max 60 chars)
    const metaTitleField = document.getElementById('meta_title');
    if (metaTitleField && !metaTitleField.value) {
        metaTitleField.value = title.substring(0, 60);
    }
    
    // Auto-generate meta description from excerpt or title (max 160 chars)
    const metaDescField = document.getElementById('meta_description');
    if (metaDescField && !metaDescField.value) {
        const description = excerpt || title;
        metaDescField.value = description.substring(0, 160);
    }
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

// Form validation before submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // CKEditor automatically syncs content to textarea
            // No manual sync needed
            
            // Validate content
            const content = document.getElementById('content_id').value;
            if (!content || content.trim() === '') {
                e.preventDefault();
                alert('Please enter article content.');
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection
