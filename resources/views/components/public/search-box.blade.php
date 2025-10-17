{{-- Search Box Component --}}
<form action="{{ route('public.search') }}" method="GET" class="relative">
    <div class="relative flex items-center">
        {{-- Search Icon --}}
        <svg class="absolute left-4 w-5 h-5 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>

        {{-- Input Field --}}
        <input 
            type="text" 
            name="q" 
            value="{{ request('q') }}"
            placeholder="{{ isset($placeholder) ? $placeholder : 'Search articles...' }}"
            class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
        >

        {{-- Submit Button --}}
        <button 
            type="submit"
            class="absolute right-2 inline-flex items-center justify-center w-9 h-9 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors"
            aria-label="Search"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    </div>

    {{-- Search Tips (Optional) --}}
    @if(isset($showTips) && $showTips)
        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            <p>💡 <strong>Tip:</strong> Use quotes for exact matches, e.g. "Laravel tips"</p>
        </div>
    @endif
</form>
