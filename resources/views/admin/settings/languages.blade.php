@extends('admin.layouts.admin')

@section('title', __('admin.language_settings'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.language_settings') }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('admin.language_settings_description') }}</p>
    </div>


    <!-- Language Settings Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.language_configuration') }}</h6>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.settings.languages.update') }}" id="languageSettingsForm">
                        @csrf
                        @method('PUT')

                        <!-- Default Language -->
                        <div class="mb-6">
                            <label for="default_language" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('admin.default_language') }}
                            </label>
                            <select name="default_language" id="default_language" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                                @foreach($languages as $language)
                                    <option value="{{ $language->code }}" 
                                            {{ $language->is_default ? 'selected' : '' }}>
                                        {{ $language->flag }} {{ $language->name }} ({{ $language->native_name }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.default_language_help') }}</p>
                        </div>

                        <!-- Active Languages -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('admin.active_languages') }}</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($languages as $language)
                                    <div class="flex items-center space-x-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <input type="checkbox" 
                                               name="active_languages[]" 
                                               value="{{ $language->code }}" 
                                               id="lang_{{ $language->code }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                               {{ $language->is_active ? 'checked' : '' }}>
                                        <label for="lang_{{ $language->code }}" class="flex-1 cursor-pointer">
                                            <span class="text-lg mr-2">{{ $language->flag }}</span>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $language->name }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $language->native_name }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.active_languages_help') }}</p>
                        </div>

                        <!-- Language Order -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('admin.language_order') }}</label>
                            <div id="languageOrder" class="space-y-2">
                                @foreach($languages->sortBy('order') as $language)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg cursor-move hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors" 
                                         data-language-id="{{ $language->id }}">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-lg">{{ $language->flag }}</span>
                                            <div>
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $language->name }}</span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">({{ $language->native_name }})</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $language->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                                {{ $language->is_active ? __('admin.active') : __('admin.inactive') }}
                                            </span>
                                            @if($language->is_default)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ __('admin.default') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="drag-handle cursor-move p-2 hover:bg-gray-200 dark:hover:bg-gray-500 rounded">
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.language_order_help') }}</p>
                        </div>

                        <!-- Browser Detection -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('admin.browser_detection') }}</label>
                            <div class="flex items-center space-x-3 p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                <input type="checkbox" 
                                       name="browser_detection" 
                                       id="browser_detection"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       checked>
                                <label for="browser_detection" class="flex-1 cursor-pointer">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ __('admin.enable_browser_detection') }}</span>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('admin.browser_detection_help') }}</p>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex space-x-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                {{ __('admin.save_settings') }}
                            </button>
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200" onclick="resetForm()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{ __('admin.reset') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Panel -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.language_statistics') }}</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 text-center mb-6">
                        <div class="p-4 border-r border-gray-200 dark:border-gray-600">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="totalLanguages">{{ $languages->count() }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.total_languages') }}</div>
                        </div>
                        <div class="p-4">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="activeLanguages">{{ $activeLanguages->count() }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.active_languages') }}</div>
                        </div>
                    </div>

                    <!-- Enhanced Statistics -->
                    <div id="statisticsContent">
                        <div class="space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2" id="totalUsers">-</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Users</div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2" id="languageUsage">-</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Language Usage</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-600 mt-6 pt-6">
                        <div class="mb-4">
                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.default_language') }}:</div>
                            <div>
                                @if($defaultLanguage)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $defaultLanguage->flag }} {{ $defaultLanguage->name }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.no_default_set') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h6 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700 dark:hover:bg-blue-800 text-sm font-medium rounded-lg transition-colors duration-200" onclick="activateAllLanguages()">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('admin.activate_all') }}
                        </button>
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 text-sm font-medium rounded-lg transition-colors duration-200" onclick="deactivateAllLanguages()">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('admin.deactivate_all') }}
                        </button>
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-cyan-300 text-cyan-700 bg-cyan-50 hover:bg-cyan-100 dark:bg-cyan-900 dark:text-cyan-200 dark:border-cyan-700 dark:hover:bg-cyan-800 text-sm font-medium rounded-lg transition-colors duration-200" onclick="clearLanguageCache()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            {{ __('admin.clear_cache') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable for language order
    const languageOrder = document.getElementById('languageOrder');
    if (languageOrder) {
        new Sortable(languageOrder, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                updateLanguageOrder();
            }
        });
    }
});

function updateLanguageOrder() {
    const order = Array.from(document.querySelectorAll('#languageOrder [data-language-id]'))
        .map(item => item.dataset.languageId);
    
    fetch('{{ route("admin.settings.languages.order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ order: order })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
        }
    });
}

// Load statistics automatically when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
});

function loadStatistics() {
    fetch('{{ route("admin.settings.languages.statistics") }}')
        .then(response => response.json())
        .then(data => {
            // Update the enhanced statistics cards
            document.getElementById('totalUsers').textContent = data.total_users || 0;
            document.getElementById('languageUsage').textContent = data.language_usage ? Object.keys(data.language_usage).length : 0;
        })
        .catch(error => {
            console.error('Failed to load statistics:', error);
            // Keep the default "-" values if loading fails
        });
}

function activateAllLanguages() {
    document.querySelectorAll('input[name="active_languages[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deactivateAllLanguages() {
    document.querySelectorAll('input[name="active_languages[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function clearLanguageCache() {
    fetch('{{ route("admin.settings.languages.clear-cache") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
        }
    });
}

function resetForm() {
    if (confirm('{{ __("admin.confirm_reset") }}')) {
        document.getElementById('languageSettingsForm').reset();
    }
}

function showNotification(type, message) {
    // Simple notification implementation with Tailwind
    const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full ${bgColor} border px-4 py-3 rounded-lg shadow-lg`;
    notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium">${message}</span>
            <button type="button" class="ml-4 text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush
