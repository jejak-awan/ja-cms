@extends('admin.layouts.admin')

@section('title', __('admin.pages.title'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.pages.title') }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('admin.pages.manage_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('admin.pages.create') }}
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">{{ __('admin.pages.stats.total') }}</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">{{ __('admin.common.published') }}</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['published'] }}</h3>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">{{ __('admin.common.draft') }}</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['draft'] }}</h3>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" placeholder="{{ __('admin.common.search') }}..." value="{{ request('search') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                <select name="status" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                    <option value="">{{ __('admin.common.all_status') }}</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ __('admin.pages.status.published') }}</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('admin.pages.status.draft') }}</option>
                </select>
                <select name="template" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200">
                    <option value="">{{ __('admin.pages.fields.all_templates') }}</option>
                    @foreach(\App\Modules\Page\Models\Page::templates() as $key => $name)
                        <option value="{{ $key }}" {{ request('template') == $key ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">{{ __('admin.common.filter') }}</button>
            </form>
        </div>

        @if($pages->count() > 0)
        <form id="bulkPagesForm" method="POST" action="{{ route('admin.pages.bulk-action') }}">
            @csrf
            <input type="hidden" name="action" id="bulkPagesActionInput">
            
            <!-- Bulk Actions Bar -->
            <div class="mb-4 flex items-center justify-between bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <div class="flex items-center space-x-4">
                    <select id="bulkPagesAction" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">{{ __('admin.common.bulk_actions') }}</option>
                        <option value="publish">{{ __('admin.common.publish_selected') }}</option>
                        <option value="draft">{{ __('admin.common.move_to_draft') }}</option>
                        <option value="delete">{{ __('admin.common.delete_selected') }}</option>
                    </select>
                    <button type="button" onclick="applyBulkPagesAction()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition">
                        {{ __('admin.common.apply') }}
                    </button>
                </div>
            </div>
            
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <input type="checkbox" id="selectAllPages" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('admin.pages.fields.title') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('admin.pages.fields.template') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('admin.common.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('admin.pages.fields.created_at') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('admin.common.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($pages as $page)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="pages[]" value="{{ $page->id }}" class="page-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                {{ $page->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">
                                {{ $page->template }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($page->status) {
                                    'published' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'draft' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded {{ $statusClass }}">
                                {{ __('admin.pages.status.' . $page->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $page->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.pages.edit', $page->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition" title="{{ __('admin.common.edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button 
                                    type="button"
                                    onclick="confirmDeletePage({{ $page->id }}, '{{ addslashes($page->title) }}')"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition" 
                                    title="{{ __('admin.common.delete') }}"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </form>

        @if($pages->hasPages())
        <div class="px-6 py-4 border-t dark:border-gray-700 dark:bg-gray-800">
            {{ $pages->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('admin.common.no_data_found') }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.pages.empty.no_pages') }}</p>
            <div class="mt-6">
                <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('admin.pages.index.btn_create') }}
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(id) {
    fetch(`/admin/pages/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function confirmDeletePage(id, title) {
    console.log('confirmDeletePage called with id:', id, 'title:', title);
    const messageEl = document.getElementById('deletePageMessage');
    const formEl = document.getElementById('deletePageForm');
    
    if (messageEl) {
        messageEl.textContent = `{{ __('admin.pages.confirm_delete') }}: "${title}"?`;
    }
    
    if (formEl) {
        // Set form action with proper base URL
        const baseUrl = '{{ url("/admin/pages") }}';
        formEl.action = `${baseUrl}/${id}`;
        console.log('Form action set to:', formEl.action);
        console.log('Form method:', formEl.method);
        
        // Verify DELETE method input exists
        const methodInput = formEl.querySelector('input[name="_method"]');
        if (methodInput) {
            console.log('DELETE method input exists, value:', methodInput.value);
        } else {
            console.error('DELETE method input NOT found!');
        }
    }
    
    console.log('Calling openModal...');
    
    // Skip Alpine.js modal, use fallback directly
    console.log('Using fallback modal directly...');
    const modal = document.getElementById('deletePageModal');
    if (modal) {
        console.log('Modal element found, showing...');
        modal.style.display = 'flex';
    } else {
        console.error('Modal element not found!');
    }
}

// Bulk actions for pages
function applyBulkPagesAction() {
    const action = document.getElementById('bulkPagesAction').value;
    if (!action) {
        alert('Please select an action');
        return;
    }

    const checkedBoxes = document.querySelectorAll('.page-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one page');
        return;
    }

    if (action === 'delete') {
        if (!confirm(`Are you sure you want to delete ${checkedBoxes.length} page(s)?`)) {
            return;
        }
    }

    document.getElementById('bulkPagesActionInput').value = action;
    document.getElementById('bulkPagesForm').submit();
}

// Select all functionality for pages
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllPages');
    const pageCheckboxes = document.querySelectorAll('.page-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            pageCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Update select all when individual checkboxes change
    pageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(pageCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(pageCheckboxes).some(cb => cb.checked);
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
        });
    });
});

// Fallback modal functions (if Alpine.js modal not working)
window.openModal = function(id) {
    console.log('Fallback openModal called for:', id);
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
    }
};

window.closeModal = function(id) {
    console.log('Fallback closeModal called for:', id);
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
    }
};
</script>
@endpush

{{-- Delete Confirmation Modal (Fallback without Alpine.js) --}}
<div id="deletePageModal" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
    <div class="absolute inset-0 bg-black/50 dark:bg-black/70" onclick="closeModal('deletePageModal')"></div>
    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-md w-full mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __('admin.common.confirm') }} {{ __('admin.common.delete') }}?
            </h3>
            <button 
                onclick="closeModal('deletePageModal')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6 text-gray-600 dark:text-gray-400">
            <p id="deletePageMessage">
                {{ __('admin.pages.confirm_delete') }}
            </p>
        </div>
        <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <button 
                type="button"
                onclick="closeModal('deletePageModal')"
                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition"
            >
                {{ __('admin.common.cancel') }}
            </button>
            <form id="deletePageForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition"
                >
                    {{ __('admin.common.delete') }}
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
