@extends('admin.layouts.admin')

@section('title', __('admin.categories.index.title'))

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <x-admin.page-header
        title="{{ __('admin.categories.index.title') }}"
        description="{{ __('admin.categories.index.description') }}"
        actionRoute="{{ route('admin.categories.create') }}"
        actionText="{{ __('admin.categories.index.btn_create') }}"
        actionIcon
    />

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    @if($errors->any())
        <x-admin.alert type="error" :message="$errors->first()" />
    @endif

    {{-- Filters & Stats --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            {{-- Search & Filters --}}
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="{{ __('admin.common.search') }} {{ __('admin.categories.fields.name') }}..."
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                >
                <select name="status" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">{{ __('admin.common.clear') }} {{ __('admin.common.status') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.common.active') }}</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.common.inactive') }}</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg font-medium transition">
                    {{ __('admin.common.filter') }}
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg font-medium transition">
                    {{ __('admin.common.reset') }}
                </a>
            </form>

            {{-- Stats --}}
            <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                    </svg>
                    <span><strong>{{ $allCategories->count() }}</strong> {{ __('admin.categories.total') }}</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>{{ $allCategories->where('is_active', true)->count() }}</strong> {{ __('admin.common.active') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories Tree/List --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        @if($categories->count() > 0)
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 inline mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H4a1 1 0 01-1-1v-3a1 1 0 011-1h.5a1.5 1.5 0 000-3H4a1 1 0 01-1-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"></path>
                        </svg>
                        {{ __('admin.categories.drag_reorder') }}
                    </p>
                </div>

                {{-- Tree View --}}
                <div id="categoryTree" class="space-y-2">
                    @foreach($categories as $category)
                        @include('admin.categories.partials.tree-item', ['category' => $category, 'level' => 0])
                    @endforeach
                </div>
            </div>
        @else
            <x-admin.empty-state
                title="{{ __('admin.common.no_data_found') }}"
                description="{{ __('admin.categories.empty_state.description') }}"
                actionRoute="{{ route('admin.categories.create') }}"
                actionText="{{ __('admin.categories.empty_state.btn_create') }}"
            />
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tree = document.getElementById('categoryTree');
    if (!tree) return;

    new Sortable(tree, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'opacity-50',
        onEnd: function(evt) {
            updateCategoryOrder();
        }
    });
});

function updateCategoryOrder() {
    const categories = Array.from(document.querySelectorAll('[data-category-id]')).map((el, index) => ({
        id: parseInt(el.dataset.categoryId),
        order: index,
        parent_id: el.dataset.parentId ? parseInt(el.dataset.parentId) : null
    }));

    fetch('{{ route("admin.categories.update-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ categories })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('{{ __('admin.categories.order_updated') }}', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('{{ __('admin.categories.order_update_failed') }}', 'error');
    });
}

function toggleStatus(categoryId) {
    fetch(`/admin/categories/${categoryId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const toggle = document.querySelector(`#status-toggle-${categoryId}`);
            if (toggle) {
                toggle.checked = data.is_active;
            }
            showNotification('{{ __('admin.categories.status_updated') }}', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('{{ __('admin.categories.status_update_failed') }}', 'error');
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} transition-all duration-300 z-50`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function confirmDelete(categoryId, categoryName) {
    if (confirm(`{{ __('admin.common.confirm') }} {{ __('admin.common.delete') }} "${categoryName}"?\n{{ __('admin.common.confirm') }}`)) {
        document.getElementById(`delete-form-${categoryId}`).submit();
    }
}
</script>
@endpush
@endsection
