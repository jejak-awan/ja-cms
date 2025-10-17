{{-- Featured Image Component --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.articles.featured_image') }}</h3>
    
    <!-- Current Image (if exists) -->
    @if(isset($article) && $article->featured_image)
        <div class="mb-4">
            <img src="{{ Storage::url($article->featured_image) }}" alt="Current image" class="w-full h-48 object-cover rounded-lg">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ __('admin.articles.current_image') }}</p>
        </div>
    @endif

    <!-- Image Upload Area -->
    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
        <input 
            type="file" 
            name="featured_image" 
            id="featured_image" 
            accept="image/*"
            onchange="previewImage(this)"
            class="hidden"
        >
        <label for="featured_image" class="cursor-pointer">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="mt-2">
                <p class="font-medium">{{ __('admin.articles.upload_image') }}</p>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium text-blue-600 hover:text-blue-500">{{ __('admin.articles.upload_image') }}</span>
                    {{ __('admin.articles.drag_and_drop') }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    PNG, JPG, GIF up to 10MB
                </p>
            </div>
        </label>
    </div>

    <!-- Image Preview -->
    <div id="imagePreview" class="mt-4 hidden">
        <img id="preview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
        <button 
            type="button" 
            onclick="removeImage()" 
            class="mt-2 px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors"
        >
            Remove Image
        </button>
    </div>

    @error('featured_image')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
