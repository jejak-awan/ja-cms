@extends('admin.layouts.admin')

@section('title', 'Theme Customization')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <x-admin.page-header
        title="Customize Theme"
        description="Personalize your admin panel with custom colors"
        icon="🎨"
    />

    {{-- Success Message --}}
    @if(session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    {{-- Admin Theme Customization --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Theme Colors</h2>
            <a href="{{ route('admin.themes.customize.reset', ['type' => 'admin']) }}" class="btn btn-secondary btn-sm">
                Reset to Defaults
            </a>
        </div>

        <form action="{{ route('admin.themes.customize.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="theme_type" value="admin">

            {{-- Color Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Primary Color --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Primary Color</label>
                    <div class="flex items-center space-x-3">
                        <input 
                            type="color" 
                            name="primary_color" 
                            value="{{ $adminCustomization->primary_color }}"
                            class="h-12 w-20 rounded cursor-pointer border border-gray-300"
                        >
                        <input 
                            type="text" 
                            name="primary_color_hex"
                            value="{{ $adminCustomization->primary_color }}"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm dark:bg-gray-700 dark:text-white"
                            readonly
                        >
                    </div>
                    <p class="text-xs text-gray-500">Used for buttons, links, and highlights</p>
                </div>

                {{-- Secondary Color --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Secondary Color</label>
                    <div class="flex items-center space-x-3">
                        <input 
                            type="color" 
                            name="secondary_color" 
                            value="{{ $adminCustomization->secondary_color }}"
                            class="h-12 w-20 rounded cursor-pointer border border-gray-300"
                        >
                        <input 
                            type="text" 
                            name="secondary_color_hex"
                            value="{{ $adminCustomization->secondary_color }}"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm dark:bg-gray-700 dark:text-white"
                            readonly
                        >
                    </div>
                    <p class="text-xs text-gray-500">Used for secondary elements</p>
                </div>

                {{-- Accent Color --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Accent Color</label>
                    <div class="flex items-center space-x-3">
                        <input 
                            type="color" 
                            name="accent_color" 
                            value="{{ $adminCustomization->accent_color }}"
                            class="h-12 w-20 rounded cursor-pointer border border-gray-300"
                        >
                        <input 
                            type="text" 
                            name="accent_color_hex"
                            value="{{ $adminCustomization->accent_color }}"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm dark:bg-gray-700 dark:text-white"
                            readonly
                        >
                    </div>
                    <p class="text-xs text-gray-500">Used for hover states and focus</p>
                </div>

                {{-- Sidebar Color --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sidebar Background</label>
                    <div class="flex items-center space-x-3">
                        <input 
                            type="color" 
                            name="sidebar_color" 
                            value="{{ $adminCustomization->sidebar_color }}"
                            class="h-12 w-20 rounded cursor-pointer border border-gray-300"
                        >
                        <input 
                            type="text" 
                            name="sidebar_color_hex"
                            value="{{ $adminCustomization->sidebar_color }}"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm dark:bg-gray-700 dark:text-white"
                            readonly
                        >
                    </div>
                    <p class="text-xs text-gray-500">Left sidebar background</p>
                </div>

                {{-- Background Color --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Background Color</label>
                    <div class="flex items-center space-x-3">
                        <input 
                            type="color" 
                            name="background_color" 
                            value="{{ $adminCustomization->background_color }}"
                            class="h-12 w-20 rounded cursor-pointer border border-gray-300"
                        >
                        <input 
                            type="text" 
                            name="background_color_hex"
                            value="{{ $adminCustomization->background_color }}"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm dark:bg-gray-700 dark:text-white"
                            readonly
                        >
                    </div>
                    <p class="text-xs text-gray-500">Main background</p>
                </div>

                {{-- Text Color --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Text Color</label>
                    <div class="flex items-center space-x-3">
                        <input 
                            type="color" 
                            name="text_color" 
                            value="{{ $adminCustomization->text_color }}"
                            class="h-12 w-20 rounded cursor-pointer border border-gray-300"
                        >
                        <input 
                            type="text" 
                            name="text_color_hex"
                            value="{{ $adminCustomization->text_color }}"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm dark:bg-gray-700 dark:text-white"
                            readonly
                        >
                    </div>
                    <p class="text-xs text-gray-500">Primary text color</p>
                </div>

                {{-- Border Color --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Border Color</label>
                    <div class="flex items-center space-x-3">
                        <input 
                            type="color" 
                            name="border_color" 
                            value="{{ $adminCustomization->border_color }}"
                            class="h-12 w-20 rounded cursor-pointer border border-gray-300"
                        >
                        <input 
                            type="text" 
                            name="border_color_hex"
                            value="{{ $adminCustomization->border_color }}"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm dark:bg-gray-700 dark:text-white"
                            readonly
                        >
                    </div>
                    <p class="text-xs text-gray-500">Borders and dividers</p>
                </div>
            </div>

            {{-- Preview Box --}}
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 space-y-4">
                <h3 class="font-medium text-gray-900 dark:text-white">Preview</h3>
                <div class="grid grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <div class="w-full h-20 rounded" style="background-color: {{ $adminCustomization->primary_color }}"></div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Primary</p>
                    </div>
                    <div class="space-y-2">
                        <div class="w-full h-20 rounded" style="background-color: {{ $adminCustomization->secondary_color }}"></div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Secondary</p>
                    </div>
                    <div class="space-y-2">
                        <div class="w-full h-20 rounded" style="background-color: {{ $adminCustomization->accent_color }}"></div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Accent</p>
                    </div>
                    <div class="space-y-2">
                        <div class="w-full h-20 rounded" style="background-color: {{ $adminCustomization->sidebar_color }}"></div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Sidebar</p>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sync color inputs with text inputs
    const colorInputs = document.querySelectorAll('input[type="color"]');
    colorInputs.forEach(input => {
        input.addEventListener('change', function() {
            const hexInput = this.parentElement.querySelector('input[readonly]');
            if (hexInput) {
                hexInput.value = this.value;
            }
        });
    });
});
</script>
@endsection
