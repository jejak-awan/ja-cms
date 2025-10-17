{{-- Select Field Component --}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'placeholder' => null,
    'hint' => null,
    'class' => '',
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
    <select 
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => "w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white {$class}"]) }}
    >
        @if($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @if(count($options) > 0)
            @foreach($options as $value => $label)
                <option value="{{ $value }}" @selected($value == old($name, $selected))>
                    {{ $label }}
                </option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
    @error($name)
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
    @if($hint)
        <p class="text-gray-500 text-sm mt-1">{{ $hint }}</p>
    @endif
</div>
