{{-- Input Field Component --}}
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => null,
    'hint' => null,
    'help' => null,
    'class' => '',
    'onkeyup' => null,
    'maxlength' => null,
])

<div class="mb-4">
    @if($label !== false && $label !== null)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-600">*</span>
            @endif
        </label>
    @endif
    <input 
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $value ?? old($name) }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $placeholder ? "placeholder=\"{$placeholder}\"" : '' }}
        {{ $onkeyup ? "onkeyup=\"{$onkeyup}\"" : '' }}
        {{ $maxlength ? "maxlength=\"{$maxlength}\"" : '' }}
        {{ $attributes->merge(['class' => "w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white {$class}"]) }}
    >
    @error($name)
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
    @if($hint || $help)
        <p class="text-gray-500 text-sm mt-1">{{ $hint ?? $help }}</p>
    @endif
</div>
