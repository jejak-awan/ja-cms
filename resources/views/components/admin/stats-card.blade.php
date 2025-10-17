{{-- Stats Card Component --}}
<div class="bg-gradient-to-br {{ $bgClass ?? 'from-blue-500 to-blue-600' }} rounded-lg shadow-lg p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-opacity-80 text-sm font-medium uppercase tracking-wide">{{ $title }}</p>
            <h3 class="text-3xl font-bold mt-2">{{ $value }}</h3>
            @if(isset($subtitle))
                <p class="text-opacity-70 text-xs mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        @if(isset($icon))
            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                {!! $icon !!}
            </div>
        @endif
    </div>
    @if(isset($trend))
        <div class="mt-4 text-sm text-opacity-80 flex items-center">
            @if($trend > 0)
                <span class="text-green-200">↑ {{ $trend }}%</span>
            @elseif($trend < 0)
                <span class="text-red-200">↓ {{ abs($trend) }}%</span>
            @else
                <span>{{ $trend }}%</span>
            @endif
            <span class="text-opacity-60 ml-2">{{ $trendLabel ?? 'from last month' }}</span>
        </div>
    @endif
</div>
