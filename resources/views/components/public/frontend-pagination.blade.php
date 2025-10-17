{{-- Frontend Pagination Component --}}
@if($items->hasPages())
    <nav aria-label="Pagination" class="flex items-center justify-center gap-2 py-8">
        {{-- Previous Page Link --}}
        @if($items->onFirstPage())
            <span class="px-3 py-2 text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </span>
        @else
            <a href="{{ $items->previousPageUrl() }}" class="px-3 py-2 text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        @endif

        {{-- Page Numbers --}}
        <div class="flex items-center gap-1">
            {{-- First Page --}}
            @if($items->currentPage() > 2)
                <a href="{{ $items->url(1) }}" class="px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    1
                </a>
                @if($items->currentPage() > 3)
                    <span class="px-2 py-2 text-gray-600 dark:text-gray-400">...</span>
                @endif
            @endif

            {{-- Current Page & Neighbors --}}
            @for($i = max(1, $items->currentPage() - 1); $i <= min($items->lastPage(), $items->currentPage() + 1); $i++)
                @if($i === $items->currentPage())
                    <span class="px-3 py-2 bg-blue-600 text-white rounded-lg font-medium">
                        {{ $i }}
                    </span>
                @else
                    <a href="{{ $items->url($i) }}" class="px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        {{ $i }}
                    </a>
                @endif
            @endfor

            {{-- Last Page --}}
            @if($items->currentPage() < $items->lastPage() - 1)
                @if($items->currentPage() < $items->lastPage() - 2)
                    <span class="px-2 py-2 text-gray-600 dark:text-gray-400">...</span>
                @endif
                <a href="{{ $items->url($items->lastPage()) }}" class="px-3 py-2 text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    {{ $items->lastPage() }}
                </a>
            @endif
        </div>

        {{-- Next Page Link --}}
        @if($items->hasMorePages())
            <a href="{{ $items->nextPageUrl() }}" class="px-3 py-2 text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        @else
            <span class="px-3 py-2 text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </span>
        @endif
    </nav>

    {{-- Pagination Info --}}
    @if(isset($showInfo) && $showInfo)
        <div class="text-center text-sm text-gray-600 dark:text-gray-400 mb-4">
            Showing <strong>{{ $items->from() }}</strong> to <strong>{{ $items->to() }}</strong> of <strong>{{ $items->total() }}</strong> results
        </div>
    @endif
@endif
