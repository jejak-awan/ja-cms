{{-- Alpine.js Live Search Component --}}
<div 
    x-data="liveSearch(@json($action ?? '/api/search'), @json($placeholder ?? 'Search...'))"
    @keydown.escape="close()"
    class="relative w-full"
>
    {{-- Search Input --}}
    <div class="relative">
        <input 
            x-model="query"
            @input.debounce.500="search()"
            @focus="open()"
            type="text"
            :placeholder="placeholder"
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:outline-none focus:border-blue-500"
            autocomplete="off"
        >
        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>

        {{-- Loading Indicator --}}
        <template x-if="loading">
            <div class="absolute right-3 top-2.5">
                <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </template>

        {{-- Clear Button --}}
        <template x-if="query && !loading">
            <button 
                @click="clear()"
                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                type="button"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </template>
    </div>

    {{-- Results Dropdown --}}
    <template x-if="isOpen && (results.length > 0 || hasSearched)">
        <div 
            class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto"
            @click.outside="close()"
        >
            {{-- Results --}}
            <template x-if="results.length > 0">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="result in results" :key="result.id">
                        <li 
                            @click="selectResult(result)"
                            class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="result.title"></p>
                                    <template x-if="result.description">
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="result.description"></p>
                                    </template>
                                </div>
                                <template x-if="result.badge">
                                    <span 
                                        class="ml-2 px-2 py-1 text-xs font-medium rounded-full"
                                        :class="result.badgeClass"
                                        x-text="result.badge"
                                    ></span>
                                </template>
                            </div>
                        </li>
                    </template>
                </ul>
            </template>

            {{-- No Results --}}
            <template x-if="results.length === 0 && hasSearched && !loading">
                <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                    <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm">No results found</p>
                </div>
            </template>

            {{-- Loading State --}}
            <template x-if="loading">
                <div class="px-4 py-6 text-center">
                    <svg class="animate-spin h-5 w-5 text-blue-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Searching...</p>
                </div>
            </template>
        </div>
    </template>

    {{-- Search Stats --}}
    <template x-if="query && results.length > 0">
        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Found <span x-text="results.length"></span> result(s) for "<span x-text="query"></span>"
        </div>
    </template>
</div>

<script>
function liveSearch(action, placeholder) {
    return {
        query: '',
        placeholder: placeholder,
        action: action,
        results: [],
        isOpen: false,
        loading: false,
        hasSearched: false,
        debounceTimer: null,

        /**
         * Perform search
         */
        async search() {
            if (!this.query.trim()) {
                this.results = [];
                this.hasSearched = false;
                return;
            }

            this.loading = true;
            this.hasSearched = true;

            try {
                const response = await fetch(`${this.action}?q=${encodeURIComponent(this.query)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (!response.ok) throw new Error('Search failed');

                const data = await response.json();
                this.results = data.results || [];
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
            } finally {
                this.loading = false;
            }
        },

        /**
         * Open results dropdown
         */
        open() {
            this.isOpen = true;
        },

        /**
         * Close results dropdown
         */
        close() {
            this.isOpen = false;
        },

        /**
         * Clear search
         */
        clear() {
            this.query = '';
            this.results = [];
            this.hasSearched = false;
            this.isOpen = false;
            this.$dispatch('search-cleared');
        },

        /**
         * Select result and trigger event
         */
        selectResult(result) {
            this.$dispatch('search-result', { result });
            this.close();
            // Optionally redirect if URL provided
            if (result.url) {
                window.location.href = result.url;
            }
        },
    };
}
</script>
