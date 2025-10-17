{{-- Textarea Field Component --}}
@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'rows' => 4,
    'placeholder' => null,
    'hint' => null,
    'help' => null,
    'class' => '',
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
    <textarea 
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $placeholder ? "placeholder=\"{$placeholder}\"" : '' }}
        {{ $maxlength ? "maxlength=\"{$maxlength}\"" : '' }}
        {{ $attributes->merge(['class' => "w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white {$class}"]) }}
    >{{ old($name, $value ?? '') }}</textarea>
    @error($name)
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
    @if($hint || $help)
        <p class="text-gray-500 text-sm mt-1">{{ $hint ?? $help }}</p>
    @endif
</div>
