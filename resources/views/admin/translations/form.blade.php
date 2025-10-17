@extends('admin.layouts.admin')

@section('title', isset($override) ? __('Edit Translation Override') : __('Add Translation Override'))

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ isset($override) ? __('Edit Translation Override') : __('Add Translation Override') }}
            </h2>
            <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to List') }}
            </a>
        </div>
    </div>

    @if ($errors->any())
        <x-admin.alert type="error" :message="__('Please correct the errors below')" />
    @endif

    <div class="max-w-4xl">
        <form method="POST" 
              action="{{ isset($override) ? route('admin.translations.update', $override) : route('admin.translations.store') }}"
              class="space-y-6">
            @csrf
            @if(isset($override))
                @method('PUT')
            @endif

            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    {{ __('Translation Details') }}
                </h3>

                <div class="space-y-6">
                    <!-- Language -->
                    <div>
                        <label for="locale" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Language') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="locale" 
                                id="locale" 
                                class="form-select @error('locale') border-red-500 @enderror"
                                {{ isset($override) ? 'disabled' : '' }}
                                required>
                            <option value="">{{ __('Select language...') }}</option>
                            @foreach(\App\Modules\Language\Models\Language::active() as $locale)
                                <option value="{{ $locale->code }}" 
                                        {{ old('locale', $override->locale ?? $prefill->locale ?? '') == $locale->code ? 'selected' : '' }}>
                                    {{ $locale->native_name }} ({{ $locale->code }})
                                </option>
                            @endforeach
                        </select>
                        @if(isset($override))
                            <input type="hidden" name="locale" value="{{ $override->locale }}">
                        @endif
                        @error('locale')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Select the language for this translation override') }}
                        </p>
                    </div>

                    <!-- Domain -->
                    <div>
                        <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Domain') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <select name="domain_select" 
                                    id="domain_select" 
                                    class="form-select flex-1"
                                    {{ isset($override) ? 'disabled' : '' }}
                                    onchange="document.getElementById('domain').value = this.value">
                                <option value="">{{ __('Select or enter custom...') }}</option>
                                <option value="messages" {{ old('domain', $override->domain ?? $prefill->domain ?? 'messages') == 'messages' ? 'selected' : '' }}>
                                    messages
                                </option>
                                <option value="validation" {{ old('domain', $override->domain ?? $prefill->domain ?? '') == 'validation' ? 'selected' : '' }}>
                                    validation
                                </option>
                                <option value="auth" {{ old('domain', $override->domain ?? $prefill->domain ?? '') == 'auth' ? 'selected' : '' }}>
                                    auth
                                </option>
                                <option value="pagination" {{ old('domain', $override->domain ?? $prefill->domain ?? '') == 'pagination' ? 'selected' : '' }}>
                                    pagination
                                </option>
                                <option value="passwords" {{ old('domain', $override->domain ?? $prefill->domain ?? '') == 'passwords' ? 'selected' : '' }}>
                                    passwords
                                </option>
                            </select>
                            <span class="flex items-center text-gray-500">{{ __('or') }}</span>
                            <input type="text" 
                                   name="domain" 
                                   id="domain" 
                                   value="{{ old('domain', $override->domain ?? $prefill->domain ?? 'messages') }}"
                                   placeholder="messages"
                                   class="form-input flex-1 @error('domain') border-red-500 @enderror"
                                   {{ isset($override) ? 'readonly' : '' }}
                                   required>
                        </div>
                        @if(isset($override))
                            <input type="hidden" name="domain" value="{{ $override->domain }}">
                        @endif
                        @error('domain')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Translation domain/namespace (e.g., messages, validation, auth)') }}
                        </p>
                    </div>

                    <!-- Key -->
                    <div>
                        <label for="key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Translation Key') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="key" 
                               id="key" 
                               value="{{ old('key', $override->key ?? $prefill->key ?? '') }}"
                               placeholder="welcome.message"
                               class="form-input @error('key') border-red-500 @enderror"
                               {{ isset($override) ? 'readonly' : '' }}
                               required>
                        @if(isset($override))
                            <input type="hidden" name="key" value="{{ $override->key }}">
                        @endif
                        @error('key')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Unique key for this translation (e.g., welcome, user.greeting)') }}
                        </p>
                    </div>

                    <!-- Value -->
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Translation Value') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea name="value" 
                                  id="value" 
                                  rows="4"
                                  placeholder="{{ __('Enter the translated text...') }}"
                                  class="form-input @error('value') border-red-500 @enderror"
                                  required>{{ old('value', $override->value ?? $prefill->value ?? '') }}</textarea>
                        @error('value')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('The translated text. You can use :placeholders for dynamic values.') }}
                        </p>
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               value="1"
                               {{ old('is_active', $override->is_active ?? true) ? 'checked' : '' }}
                               class="form-checkbox h-5 w-5 text-blue-600">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            {{ __('Active (override will be applied immediately)') }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- Original Translation (if editing) -->
            @if(isset($override))
                @php
                    $original = __($override->key, [], $override->domain, $override->locale, false);
                @endphp
                @if($original && $original !== $override->key)
                    <div class="card bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">
                            {{ __('Original Translation') }}
                        </h4>
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            {{ $original }}
                        </p>
                    </div>
                @endif
            @endif

            <!-- Preview -->
            <div class="card bg-gray-50 dark:bg-gray-800">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ __('Preview') }}
                </h4>
                <p id="preview" class="text-sm text-gray-700 dark:text-gray-300 italic">
                    {{ old('value', $override->value ?? $prefill->value ?? __('Type to preview...')) }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($override) ? __('Update Override') : __('Create Override') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Live preview
        document.getElementById('value').addEventListener('input', function(e) {
            const preview = document.getElementById('preview');
            preview.textContent = e.target.value || '{{ __('Type to preview...') }}';
        });
    </script>
    @endpush
</div>
@endsection
