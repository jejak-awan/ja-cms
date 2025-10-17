{{-- Template Selection Component for Pages --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        {{ __('admin.pages.template') }}
    </h3>
    
    <div>
        <select 
            id="template"
            name="template"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
            <option value="">{{ __('admin.pages.fields.all_templates') }}</option>
            @foreach($templates as $key => $template)
                <option value="{{ $key }}" {{ old('template', $page->template ?? '') == $key ? 'selected' : '' }}>
                    {{ $template }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('admin.pages.template_help') }}</p>
    </div>
</div>
