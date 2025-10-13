@extends('admin.layouts.admin')

@section('title', 'Edit Page')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Page</h2>
            <p class="text-sm text-gray-600 mt-1">Update page information</p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Pages
        </a>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium mb-2">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required class="w-full px-4 py-2 border rounded-lg" onkeyup="generateSlug()">
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium mb-2">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}" class="w-full px-4 py-2 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from title</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium mb-2">Excerpt</label>
                    <textarea name="excerpt" rows="3" class="w-full px-4 py-2 border rounded-lg">{{ old('excerpt', $page->excerpt) }}</textarea>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <label class="block text-sm font-medium mb-2">Content *</label>
                    <textarea name="content" id="content" rows="15" required class="w-full px-4 py-2 border rounded-lg">{{ old('content', $page->content) }}</textarea>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">SEO Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="w-full px-4 py-2 border rounded-lg">{{ old('meta_description', $page->meta_description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Publish</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Status *</label>
                            <select name="status" class="w-full px-4 py-2 border rounded-lg">
                                <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_homepage" value="1" {{ old('is_homepage', $page->is_homepage) ? 'checked' : '' }} class="rounded">
                                <span class="ml-2 text-sm">Set as Homepage</span>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Update Page</button>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Template</h3>
                    <select name="template" class="w-full px-4 py-2 border rounded-lg">
                        @foreach($templates as $key => $name)
                            <option value="{{ $key }}" {{ old('template', $page->template) == $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Featured Image</h3>
                    @if($page->featured_image)
                    <div class="mb-3">
                        <img src="{{ Storage::url($page->featured_image) }}" class="max-h-48 rounded mx-auto">
                        <p class="text-xs text-gray-500 text-center mt-2">Current Image</p>
                    </div>
                    @endif
                    <input type="file" name="featured_image" id="featured_image" class="hidden" onchange="previewImage(this)">
                    <label for="featured_image" class="block border-2 border-dashed rounded-lg p-4 text-center cursor-pointer hover:bg-gray-50">
                        <div id="imagePreview" class="hidden"><img id="preview" class="mx-auto max-h-48 rounded"></div>
                        <div id="imagePlaceholder">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 mt-2">Upload New Image</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.initTinyMCE === 'function') {
        window.initTinyMCE('#content', { height: 500 });
    }
});

function generateSlug() {
    const title = document.getElementById('title').value;
    const slug = title.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').trim();
    document.getElementById('slug').value = slug;
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('imagePlaceholder').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
