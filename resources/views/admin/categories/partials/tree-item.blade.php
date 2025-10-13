<div class="category-item" data-category-id="{{ $category->id }}" data-parent-id="{{ $category->parent_id }}" style="margin-left: {{ $level * 2 }}rem;">
    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition group">
        <!-- Left: Drag Handle + Icon + Info -->
        <div class="flex items-center space-x-3 flex-1">
            <!-- Drag Handle -->
            <div class="drag-handle cursor-move opacity-0 group-hover:opacity-100 transition">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                </svg>
            </div>

            <!-- Category Icon -->
            <div class="flex-shrink-0">
                @if($category->icon)
                    <img src="{{ Storage::url($category->icon) }}" alt="{{ $category->name }}" class="w-10 h-10 rounded object-cover">
                @else
                    <div class="w-10 h-10 rounded flex items-center justify-center" style="background-color: {{ $category->color ?? '#e5e7eb' }}">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Category Info -->
            <div class="flex-1">
                <div class="flex items-center space-x-2">
                    <h4 class="text-sm font-medium text-gray-900">{{ $category->name }}</h4>
                    @if($category->parent)
                        <span class="text-xs text-gray-500">→ {{ $category->parent->name }}</span>
                    @endif
                </div>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-xs text-gray-500">{{ $category->slug }}</span>
                    @if($category->articles_count > 0)
                        <span class="text-xs text-blue-600 font-medium">{{ $category->articles_count }} articles</span>
                    @endif
                    @if($category->children->count() > 0)
                        <span class="text-xs text-purple-600 font-medium">{{ $category->children->count() }} subcategories</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Status Toggle + Actions -->
        <div class="flex items-center space-x-3">
            <!-- Active Toggle -->
            <label class="relative inline-flex items-center cursor-pointer">
                <input 
                    type="checkbox" 
                    id="status-toggle-{{ $category->id }}"
                    {{ $category->is_active ? 'checked' : '' }} 
                    class="sr-only peer"
                    onchange="toggleStatus({{ $category->id }})"
                >
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                <span class="ml-2 text-xs font-medium text-gray-700">
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                </span>
            </label>

            <!-- Edit Button -->
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>

            <!-- Delete Button -->
            <button onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')" class="text-red-600 hover:text-red-900" title="Delete">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>

            <!-- Hidden Delete Form -->
            <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- Child Categories (Recursive) -->
    @if($category->children->count() > 0)
        <div class="mt-2 space-y-2">
            @foreach($category->children as $child)
                @include('admin.categories.partials.tree-item', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
