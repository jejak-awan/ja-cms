{{-- Stats Grid Component --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $columns ?? 4 }} gap-4 md:gap-6 {{ $class ?? 'mb-6' }}">
    {{ $slot }}
</div>
