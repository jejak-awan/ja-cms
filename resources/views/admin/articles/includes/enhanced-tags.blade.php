{{-- Enhanced Tags Component --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
        </svg>
        {{ __('admin.articles.tags_label') }}
    </h3>
    
    <!-- Manual Tag Input -->
    <div class="mb-4">
        <label for="new_tag_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ __('admin.articles.add_new_tag') }}
        </label>
        <div class="flex gap-2">
            <input 
                type="text" 
                id="new_tag_input" 
                placeholder="{{ __('admin.articles.enter_tag_name') }}"
                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                onkeypress="handleTagInput(event)"
            >
            <button 
                type="button" 
                onclick="addNewTag()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            >
                {{ __('admin.articles.add_tag') }}
            </button>
        </div>
    </div>

    <!-- Auto Tags Toggle -->
    <div class="mb-4">
        <label class="flex items-center">
            <input 
                type="checkbox" 
                name="auto_tags" 
                value="1"
                {{ old('auto_tags') ? 'checked' : '' }}
                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
            >
            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                {{ __('admin.articles.auto_generate_tags') }}
            </span>
        </label>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            {{ __('admin.articles.auto_tags_help') }}
        </p>
    </div>

    <!-- Auto Tags Button -->
    <div class="mb-4">
        <button 
            type="button" 
            onclick="generateAutoTags()"
            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors"
        >
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            {{ __('admin.articles.generate_auto_tags') }}
        </button>
    </div>

    <!-- Suggest Tags Button -->
    <div class="mb-4">
        <button 
            type="button" 
            onclick="suggestTags()"
            class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors"
        >
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
            {{ __('admin.articles.suggest_tags') }}
        </button>
    </div>

    <!-- Selected Tags Display -->
    <div id="selected_tags_display" class="mb-4">
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ __('admin.articles.selected_tags') }} (<span id="tag_count">0</span>)
        </h4>
        <div id="selected_tags_list" class="flex flex-wrap gap-2">
            <!-- Selected tags will appear here -->
        </div>
    </div>

    <!-- Available Tags -->
    <div>
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ __('admin.articles.available_tags') }}
        </h4>
        <div class="space-y-2 max-h-40 overflow-y-auto">
            @foreach($tags as $tag)
                <label class="flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded">
                    <input 
                        type="checkbox" 
                        name="tags[]" 
                        value="{{ $tag->id }}" 
                        {{ in_array($tag->id, old('tags', isset($article) ? $article->tags->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                        class="tag-checkbox rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                        onchange="toggleTag(this, {{ $tag->id }}, '{{ $tag->name }}')"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                    @if($tag->description)
                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">({{ $tag->description }})</span>
                    @endif
                </label>
            @endforeach
        </div>
        @if($tags->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.articles.no_tags') }}</p>
        @endif
    </div>
</div>
