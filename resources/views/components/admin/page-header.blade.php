{{-- Page Header Component --}}
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
        @if(isset($description))
            <p class="text-sm text-gray-600 mt-1">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actionRoute) && isset($actionText))
        <a href="{{ $actionRoute }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition shadow-sm">
            @if(isset($actionIcon))
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            @endif
            {{ $actionText }}
        </a>
    @endif
</div>
