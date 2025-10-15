@extends('admin.layouts.app')

@section('title', __('admin.language_settings'))

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ __('admin.language_settings') }}</h1>
            <p class="text-muted">{{ __('admin.language_settings_description') }}</p>
        </div>
        <div>
            <button type="button" class="btn btn-info" onclick="loadStatistics()">
                <i class="fas fa-chart-bar"></i> {{ __('admin.statistics') }}
            </button>
        </div>
    </div>

    <!-- Language Settings Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.language_configuration') }}</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.languages.update') }}" id="languageSettingsForm">
                        @csrf
                        @method('PUT')

                        <!-- Default Language -->
                        <div class="form-group">
                            <label for="default_language" class="form-label font-weight-bold">
                                {{ __('admin.default_language') }}
                            </label>
                            <select name="default_language" id="default_language" class="form-control" required>
                                @foreach($languages as $language)
                                    <option value="{{ $language->code }}" 
                                            {{ $language->is_default ? 'selected' : '' }}>
                                        {{ $language->flag }} {{ $language->name }} ({{ $language->native_name }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">{{ __('admin.default_language_help') }}</small>
                        </div>

                        <!-- Active Languages -->
                        <div class="form-group">
                            <label class="form-label font-weight-bold">{{ __('admin.active_languages') }}</label>
                            <div class="row">
                                @foreach($languages as $language)
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   name="active_languages[]" 
                                                   value="{{ $language->code }}" 
                                                   id="lang_{{ $language->code }}"
                                                   class="form-check-input"
                                                   {{ $language->is_active ? 'checked' : '' }}>
                                            <label for="lang_{{ $language->code }}" class="form-check-label">
                                                <span class="mr-2">{{ $language->flag }}</span>
                                                <strong>{{ $language->name }}</strong>
                                                <small class="text-muted">({{ $language->native_name }})</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">{{ __('admin.active_languages_help') }}</small>
                        </div>

                        <!-- Language Order -->
                        <div class="form-group">
                            <label class="form-label font-weight-bold">{{ __('admin.language_order') }}</label>
                            <div id="languageOrder" class="list-group">
                                @foreach($languages->sortBy('order') as $language)
                                    <div class="list-group-item d-flex justify-content-between align-items-center" 
                                         data-language-id="{{ $language->id }}">
                                        <div>
                                            <span class="mr-2">{{ $language->flag }}</span>
                                            <strong>{{ $language->name }}</strong>
                                            <small class="text-muted">({{ $language->native_name }})</small>
                                        </div>
                                        <div>
                                            <span class="badge badge-{{ $language->is_active ? 'success' : 'secondary' }}">
                                                {{ $language->is_active ? __('admin.active') : __('admin.inactive') }}
                                            </span>
                                            @if($language->is_default)
                                                <span class="badge badge-primary">{{ __('admin.default') }}</span>
                                            @endif
                                        </div>
                                        <div class="drag-handle">
                                            <i class="fas fa-grip-vertical text-muted"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">{{ __('admin.language_order_help') }}</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('admin.save_settings') }}
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-undo"></i> {{ __('admin.reset') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Panel -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.language_statistics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <div class="h4 text-primary" id="totalLanguages">{{ $languages->count() }}</div>
                                <div class="text-muted">{{ __('admin.total_languages') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-success" id="activeLanguages">{{ $activeLanguages->count() }}</div>
                            <div class="text-muted">{{ __('admin.active_languages') }}</div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <strong>{{ __('admin.default_language') }}:</strong>
                        <div class="mt-1">
                            @if($defaultLanguage)
                                <span class="badge badge-primary">
                                    {{ $defaultLanguage->flag }} {{ $defaultLanguage->name }}
                                </span>
                            @else
                                <span class="text-muted">{{ __('admin.no_default_set') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>{{ __('admin.browser_detection') }}:</strong>
                        <div class="mt-1">
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> {{ __('admin.enabled') }}
                            </span>
                        </div>
                        <small class="text-muted">{{ __('admin.browser_detection_help') }}</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('admin.quick_actions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="activateAllLanguages()">
                            <i class="fas fa-check-circle"></i> {{ __('admin.activate_all') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deactivateAllLanguages()">
                            <i class="fas fa-times-circle"></i> {{ __('admin.deactivate_all') }}
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="clearLanguageCache()">
                            <i class="fas fa-sync"></i> {{ __('admin.clear_cache') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Modal -->
<div class="modal fade" id="statisticsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.language_statistics') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="statisticsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">{{ __('admin.loading') }}...</span>
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

function loadStatistics() {
    $('#statisticsModal').modal('show');
    
    fetch('{{ route("admin.settings.languages.statistics") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('statisticsContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>{{ __('admin.usage_statistics') }}</h6>
                        <canvas id="usageChart" width="300" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('admin.language_distribution') }}</h6>
                        <div id="languageDistribution">
                            ${Object.entries(data.language_usage).map(([lang, percentage]) => `
                                <div class="d-flex justify-content-between mb-2">
                                    <span>${lang.toUpperCase()}</span>
                                    <span>${percentage}%</span>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar" style="width: ${percentage}%"></div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
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
    // Simple notification implementation
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush
