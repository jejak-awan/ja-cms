{{-- Publish Settings Component --}}
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
            :selected="old('status', $article->status ?? 'draft')"
        />
    </div>

    <!-- Publish Date -->
    <div class="mb-4">
        <x-admin.input-field
            name="published_at"
            :label="__('admin.articles.publish_date')"
            type="datetime-local"
            :value="old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '')"
            :help="__('admin.articles.publish_immediately')"
        />
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col space-y-2 pt-4 border-t dark:border-gray-700">
        <x-admin.button type="submit" variant="primary" class="w-full">
            {{ isset($article) ? __('admin.articles.update') : __('admin.articles.create') }}
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
