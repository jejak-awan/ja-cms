{{-- Publish Settings Component for Pages --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        {{ __('admin.pages.publish_settings') }}
    </h3>
    
    <div class="space-y-4">
        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('admin.pages.fields.status') }}
                <span class="text-red-600">*</span>
            </label>
            <select 
                id="status"
                name="status"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="draft" {{ old('status', $page->status ?? 'draft') == 'draft' ? 'selected' : '' }}>
                    {{ __('admin.pages.status_draft') }}
                </option>
                <option value="published" {{ old('status', $page->status ?? 'draft') == 'published' ? 'selected' : '' }}>
                    {{ __('admin.pages.status_published') }}
                </option>
            </select>
        </div>
        
        <!-- Homepage Setting -->
        <div>
            <label class="flex items-center cursor-pointer">
                <input 
                    type="checkbox" 
                    name="is_homepage" 
                    value="1" 
                    {{ old('is_homepage', $page->is_homepage ?? false) ? 'checked' : '' }} 
                    class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700"
                >
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('admin.pages.set_homepage') }}</span>
            </label>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.pages.set_homepage_help') }}</p>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex flex-col space-y-2 pt-4 border-t dark:border-gray-700">
        <x-admin.button type="submit" variant="primary" class="w-full">
            {{ isset($page) ? __('admin.pages.update') : __('admin.pages.create') }}
        </x-admin.button>
        <x-admin.button 
            type="link" 
            :href="route('admin.pages.index')" 
            variant="secondary" 
            class="w-full text-center"
        >
            {{ __('admin.common.cancel') }}
        </x-admin.button>
    </div>
</div>
