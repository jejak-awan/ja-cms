{{-- Button Component --}}
@props([
    'type' => 'button',
    'variant' => 'primary',
    'href' => null,
    'class' => '',
])

@php
$baseClasses = 'inline-flex items-center px-4 py-2 rounded-lg font-medium transition shadow-sm';
$variants = [
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white dark:bg-blue-500 dark:hover:bg-blue-600',
    'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    'success' => 'bg-green-600 hover:bg-green-700 text-white',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
    'info' => 'bg-blue-500 hover:bg-blue-600 text-white',
];
$variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

@if($type === 'link')
    <a href="{{ $href ?? '#' }}" {{ $attributes->merge(['class' => "{$baseClasses} {$variantClass} {$class}"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "{$baseClasses} {$variantClass} {$class}"]) }}>
        {{ $slot }}
    </button>
@endif
