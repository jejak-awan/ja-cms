@extends('admin.layouts.admin')

@section('title', 'Edit Article')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Article</h2>
            <p class="text-sm text-gray-600 mt-1">Update your blog post</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Articles
        </a>
    </div>

    <!-- Errors -->
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-medium">There were some errors with your submission:</p>
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
    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title_id" 
                        id="title" 
                        value="{{ old('title_id', $article->title) }}" 
                        required
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title_id') border-red-500 @enderror"
                        placeholder="Enter article title..."
                        onkeyup="generateSlug()"
                    >
                    @error('title_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        URL Slug
                    </label>
                    <div class="flex items-center">
                        <span class="text-gray-500 text-sm mr-2">{{ url('/') }}/</span>
                        <input 
                            type="text" 
                            name="slug" 
                            id="slug" 
                            value="{{ old('slug', $article->slug) }}" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                            placeholder="auto-generated-from-title"
                        >
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from title</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                        Excerpt
                    </label>
                    <textarea 
                        name="excerpt_id" 
                        id="excerpt" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('excerpt_id') border-red-500 @enderror"
                        placeholder="Brief summary of the article (optional)"
                    >{{ old('excerpt_id', $article->excerpt) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">A short description that appears in article listings</p>
                    @error('excerpt_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content Editor -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="content_id" 
                        id="content" 
                        rows="15"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content_id') border-red-500 @enderror"
                    >{{ old('content_id', $article->content) }}</textarea>
                    @error('content_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SEO Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        SEO Settings
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Title
                            </label>
                            <input 
                                type="text" 
                                name="meta_title" 
                                id="meta_title" 
                                value="{{ old('meta_title', $article->meta_title) }}" 
                                maxlength="60"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="SEO title for search engines"
                            >
                            <p class="mt-1 text-xs text-gray-500">Recommended: 50-60 characters</p>
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea 
                                name="meta_description" 
                                id="meta_description" 
                                rows="3"
                                maxlength="160"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Brief description for search results"
                            >{{ old('meta_description', $article->meta_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Keywords
                            </label>
                            <input 
                                type="text" 
                                name="meta_keywords" 
                                id="meta_keywords" 
                                value="{{ old('meta_keywords', $article->meta_keywords) }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="keyword1, keyword2, keyword3"
                            >
                            <p class="mt-1 text-xs text-gray-500">Comma-separated keywords</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish Settings -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Publish</h3>
                    
                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="status" 
                            id="status" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="scheduled" {{ old('status', $article->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                    </div>

                    <!-- Publish Date -->
                    <div class="mb-4">
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Publish Date
                        </label>
                        <input 
                            type="datetime-local" 
                            name="published_at" 
                            id="published_at" 
                            value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <p class="mt-1 text-xs text-gray-500">Leave empty to publish immediately</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col space-y-2 pt-4 border-t">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                            Update Article
                        </button>
                        <a href="{{ route('admin.articles.index') }}" class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition text-center">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Category -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Category</h3>
                    <select 
                        name="category_id" 
                        id="category_id" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
                    >
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
                    <div class="space-y-2">
                        @foreach($tags as $tag)
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="tags[]" 
                                    value="{{ $tag->id }}" 
                                    {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                >
                                <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @if($tags->isEmpty())
                        <p class="text-sm text-gray-500">No tags available</p>
                    @endif
                </div>

                <!-- Featured Image -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Featured Image</h3>
                    
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
                                <p class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium text-blue-600 hover:text-blue-500">{{ $article->featured_image ? 'Change image' : 'Upload an image' }}</span>
                                    or drag and drop
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
// Initialize TinyMCE (loaded from local via Vite)
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing TinyMCE...');
    console.log('initTinyMCE function available:', typeof window.initTinyMCE);
    
    if (typeof window.initTinyMCE === 'function') {
        window.initTinyMCE('[name="content_id"]', {
            height: 500,
            placeholder: 'Start writing your article content here...'
        });
        console.log('TinyMCE initialized');
    } else {
        console.error('initTinyMCE function not found. Check if app.js is loaded.');
    }
});

// Slug generator
function generateSlug() {
    const title = document.getElementById('title').value;
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
