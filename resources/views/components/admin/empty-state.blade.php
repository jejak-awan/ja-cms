{{-- Empty State Component --}}
<div class="text-center py-12">
    <div class="inline-block mb-4">
        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $title ?? __('admin.common.no_data_found') }}</h3>
    <p class="text-gray-600 mb-6">{{ $description ?? __('admin.common.no_data_description') }}</p>
    @if(isset($actionRoute) && isset($actionText))
        <a href="{{ $actionRoute }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
            {{ $actionText }}
        </a>
    @endif
</div>
