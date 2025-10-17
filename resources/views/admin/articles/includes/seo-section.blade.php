{{-- SEO Section Component --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                value="{{ old('meta_title', $article->meta_title ?? '') }}" 
                maxlength="60"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="{{ __('admin.articles.meta_title') }}"
                data-original-title="{{ $article->meta_title ?? '' }}"
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
            >{{ old('meta_description', $article->meta_description ?? '') }}</textarea>
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
                value="{{ old('meta_keywords', $article->meta_keywords ?? '') }}" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="{{ __('admin.articles.meta_keywords') }}"
            >
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.articles.meta_keywords_help') }}</p>
        </div>
    </div>
</div>
