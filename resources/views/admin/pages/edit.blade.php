@extends('admin.layouts.admin')

@section('title', 'Edit Page')

@section('content')
<div class="space-y-6">
    <x-admin.page-header
        title="Edit Page"
        subtitle="Update page information"
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
                Back to Pages
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

    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.input-field
                        name="title_id"
                        label="Title"
                        type="text"
                        :value="old('title_id', $page->title)"
                        required
                        placeholder="Enter page title..."
                        class="text-lg"
                        onkeyup="generateSlug()"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.input-field
                        name="slug"
                        label="Slug"
                        :value="old('slug', $page->slug)"
                        placeholder="auto-generated-from-title"
                        help="Leave empty to auto-generate from title"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.textarea-field
                        name="excerpt"
                        label="Excerpt"
                        :value="old('excerpt', $page->excerpt)"
                        rows="3"
                        placeholder="Brief summary (optional)"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <x-admin.textarea-field
                        name="content_id"
                        label="Content"
                        :value="old('content_id', $page->content)"
                        rows="15"
                        required
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">SEO Settings</h3>
                    <div class="space-y-4">
                        <x-admin.input-field
                            name="meta_title"
                            label="Meta Title"
                            :value="old('meta_title', $page->meta_title)"
                            placeholder="SEO title for search engines"
                        />
                        
                        <x-admin.textarea-field
                            name="meta_description"
                            label="Meta Description"
                            :value="old('meta_description', $page->meta_description)"
                            rows="3"
                            placeholder="Brief description for search results"
                        />
                        
                        <x-admin.input-field
                            name="meta_keywords"
                            label="Meta Keywords"
                            :value="old('meta_keywords', $page->meta_keywords)"
                            placeholder="keyword1, keyword2, keyword3"
                        />
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Publish</h3>
                    <div class="space-y-4">
                        <x-admin.select-field
                            name="status"
                            label="Status"
                            required
                            :options="['draft' => 'Draft', 'published' => 'Published']"
                            :selected="old('status', $page->status)"
                        />
                        
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_homepage" value="1" {{ old('is_homepage', $page->is_homepage) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Set as Homepage</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t dark:border-gray-700">
                        <div class="flex gap-3">
                            <x-admin.button type="submit" variant="primary" class="flex-1">
                                @t('admin.actions.update')
                            </x-admin.button>
                            <x-admin.button type="link" :href="route('admin.pages.index')" variant="secondary" class="flex-1">
                                @t('admin.actions.cancel')
                            </x-admin.button>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Template</h3>
                    <x-admin.select-field
                        name="template"
                        :label="false"
                        :options="$templates"
                        :selected="old('template', $page->template)"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Featured Image</h3>
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
document.addEventListener('DOMContentLoaded', async function() {
    if (typeof window.initCKEditor === 'function') {
        const textarea = document.querySelector('[name="content_id"]');
        if (textarea) {
            try {
                await window.initCKEditor(textarea, {
                    placeholder: 'Start writing your page content...',
                    uploadUrl: '{{ route("admin.upload.image") }}'
                });
                console.log('✓ CKEditor initialized');
            } catch (error) {
                console.error('✗ CKEditor initialization failed:', error);
            }
        }
    }
});

function generateSlug() {
    const title = document.getElementById('title_id').value;
    const excerpt = document.getElementById('excerpt') ? document.getElementById('excerpt').value : '';
    
    // Generate slug
    const slug = title.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').trim();
    document.getElementById('slug').value = slug;
    
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
                alert('Please enter page content.');
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection
