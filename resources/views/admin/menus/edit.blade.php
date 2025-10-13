@extends('admin.layouts.admin')

@section('title', 'Edit Menu: ' . $menu->display_name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Menu: {{ $menu->display_name }}</h2>
            <p class="text-sm text-gray-600 mt-1">Drag items to reorder, nest items by dragging right</p>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back to Menus
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Panel: Add Items -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Menu Settings -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Menu Settings</h3>
                <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                            <input type="text" name="display_name" value="{{ $menu->display_name }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="2" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ $menu->description }}</textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ $menu->is_active ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 rounded">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">
                            Update Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Add Items Tabs -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button onclick="switchTab('pages')" id="tab-pages" class="tab-button flex-1 py-3 px-4 text-sm font-medium border-b-2 border-blue-600 text-blue-600">
                            Pages
                        </button>
                        <button onclick="switchTab('categories')" id="tab-categories" class="tab-button flex-1 py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                            Categories
                        </button>
                        <button onclick="switchTab('custom')" id="tab-custom" class="tab-button flex-1 py-3 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                            Custom
                        </button>
                    </nav>
                </div>

                <div class="p-4">
                    <!-- Pages Tab -->
                    <div id="content-pages" class="tab-content">
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($pages as $page)
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                                <input type="checkbox" class="page-checkbox w-4 h-4 text-blue-600 rounded" value="{{ $page->id }}" data-title="{{ $page->title }}">
                                <span class="ml-2 text-sm text-gray-700">{{ $page->title }}</span>
                            </label>
                            @empty
                            <p class="text-sm text-gray-500">No published pages found</p>
                            @endforelse
                        </div>
                        @if($pages->count())
                        <button onclick="addPages()" class="mt-3 w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            Add Selected Pages
                        </button>
                        @endif
                    </div>

                    <!-- Categories Tab -->
                    <div id="content-categories" class="tab-content hidden">
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($categories as $category)
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                                <input type="checkbox" class="category-checkbox w-4 h-4 text-blue-600 rounded" value="{{ $category->id }}" data-title="{{ $category->name }}">
                                <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                            </label>
                            @empty
                            <p class="text-sm text-gray-500">No categories found</p>
                            @endforelse
                        </div>
                        @if($categories->count())
                        <button onclick="addCategories()" class="mt-3 w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            Add Selected Categories
                        </button>
                        @endif
                    </div>

                    <!-- Custom Link Tab -->
                    <div id="content-custom" class="tab-content hidden">
                        <form onsubmit="addCustomLink(event)" class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" id="custom-title" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                                <input type="text" id="custom-url" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="https://" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                                <select id="custom-target" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="_self">Same Window</option>
                                    <option value="_blank">New Window</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                Add Custom Link
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Menu Structure -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900">Menu Structure</h3>
                    <button onclick="saveOrder()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                        Save Order
                    </button>
                </div>

                <div id="menu-items" class="nested-sortable space-y-2 min-h-[200px]">
                    @forelse($items as $item)
                        @include('admin.menus.partials.menu-item', ['item' => $item])
                    @empty
                    <div class="text-center py-12 text-gray-500">
                        <p>No menu items yet. Add items from the left panel.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
const menuId = {{ $menu->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Initialize nested Sortable
function initSortable(element) {
    return new Sortable(element, {
        group: 'nested',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        handle: '.drag-handle',
        ghostClass: 'opacity-50',
        dragClass: 'bg-blue-50',
        onEnd: function() {
            console.log('Drag ended - ready to save order');
        }
    });
}

// Init main list and all nested lists
const mainList = document.querySelector('#menu-items');
if (mainList) {
    initSortable(mainList);
}

document.querySelectorAll('.nested-children').forEach(el => {
    initSortable(el);
});

// Tab Switching
function switchTab(tab) {
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-blue-600', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.getElementById('tab-' + tab).classList.add('border-blue-600', 'text-blue-600');
    document.getElementById('tab-' + tab).classList.remove('border-transparent', 'text-gray-500');
    document.getElementById('content-' + tab).classList.remove('hidden');
}

// Add Pages
function addPages() {
    const checkboxes = document.querySelectorAll('.page-checkbox:checked');
    const promises = [];
    
    checkboxes.forEach(checkbox => {
        promises.push(
            fetch(`/admin/menus/${menuId}/items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type: 'page',
                    page_id: checkbox.value,
                    title: checkbox.dataset.title
                })
            }).then(r => r.json())
        );
        checkbox.checked = false;
    });
    
    Promise.all(promises).then(() => {
        location.reload(); // Reload to reinitialize sortable with new items
    });
}

// Add Categories
function addCategories() {
    const checkboxes = document.querySelectorAll('.category-checkbox:checked');
    const promises = [];
    
    checkboxes.forEach(checkbox => {
        promises.push(
            fetch(`/admin/menus/${menuId}/items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type: 'category',
                    category_id: checkbox.value,
                    title: checkbox.dataset.title
                })
            }).then(r => r.json())
        );
        checkbox.checked = false;
    });
    
    Promise.all(promises).then(() => location.reload());
}

// Add Custom Link
function addCustomLink(e) {
    e.preventDefault();
    
    const title = document.getElementById('custom-title').value;
    const url = document.getElementById('custom-url').value;
    const target = document.getElementById('custom-target').value;
    
    fetch(`/admin/menus/${menuId}/items`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            type: 'custom',
            title: title,
            url: url,
            target: target
        })
    })
    .then(r => r.json())
    .then(() => {
        document.getElementById('custom-title').value = '';
        document.getElementById('custom-url').value = '';
        location.reload();
    });
}

// Delete Item
function deleteItem(id) {
    if (!confirm('Delete this menu item?')) return;
    
    fetch(`/admin/menu-items/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(() => location.reload());
}

// Toggle Edit
function toggleEdit(id) {
    const view = document.getElementById(`view-${id}`);
    const edit = document.getElementById(`edit-${id}`);
    view.classList.toggle('hidden');
    edit.classList.toggle('hidden');
}

// Update Item
function updateItem(id) {
    const title = document.getElementById(`title-${id}`).value;
    const url = document.getElementById(`url-${id}`).value;
    const target = document.getElementById(`target-${id}`).value;
    
    fetch(`/admin/menu-items/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ title, url, target })
    })
    .then(r => r.json())
    .then(() => location.reload());
}

// Save Order
function saveOrder() {
    const items = collectItems(document.querySelector('#menu-items'));
    
    fetch(`/admin/menus/${menuId}/order`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ items })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Menu order saved successfully!');
        }
    });
}

// Collect items recursively with parent_id
function collectItems(container, parentId = null) {
    const items = [];
    const children = Array.from(container.children).filter(el => el.classList.contains('menu-item'));
    
    children.forEach((el, index) => {
        const itemId = parseInt(el.dataset.id);
        items.push({
            id: itemId,
            parent_id: parentId,
            order: index
        });
        
        // Check for nested children
        const nestedContainer = el.querySelector('.nested-children');
        if (nestedContainer) {
            const nestedItems = collectItems(nestedContainer, itemId);
            items.push(...nestedItems);
        }
    });
    
    return items;
}
</script>
@endpush
@endsection
