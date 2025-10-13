@extends('admin.layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Categories</h2>
            <p class="text-sm text-gray-600 mt-1">Manage your content categories and hierarchies</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Category
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Filters & Stats -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Search & Filters -->
            <form method="GET" class="flex items-center space-x-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search categories..."
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    Filter
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                    Reset
                </a>
            </form>

            <!-- Stats -->
            <div class="flex items-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                    </svg>
                    <span><strong>{{ $allCategories->count() }}</strong> total</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>{{ $allCategories->where('is_active', true)->count() }}</strong> active</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Tree/List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($categories->count() > 0)
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-600">
                        <svg class="w-4 h-4 inline mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H4a1 1 0 01-1-1v-3a1 1 0 011-1h.5a1.5 1.5 0 000-3H4a1 1 0 01-1-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"></path>
                        </svg>
                        Drag and drop to reorder categories
                    </p>
                </div>

                <!-- Tree View -->
                <div id="categoryTree" class="space-y-2">
                    @foreach($categories as $category)
                        @include('admin.categories.partials.tree-item', ['category' => $category, 'level' => 0])
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No categories found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Category
                    </a>
                </div>
            </div>
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
        ghostClass: 'bg-blue-50',
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
            showNotification('Order updated successfully!', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to update order', 'error');
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
            showNotification('Status updated!', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to update status', 'error');
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
    if (confirm(`Are you sure you want to delete "${categoryName}"?\nThis action cannot be undone.`)) {
        document.getElementById(`delete-form-${categoryId}`).submit();
    }
}
</script>
@endpush
@endsection
