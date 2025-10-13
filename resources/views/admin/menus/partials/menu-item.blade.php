<div class="menu-item bg-white border border-gray-200 rounded-lg" data-id="{{ $item->id }}">
    <!-- View Mode -->
    <div id="view-{{ $item->id }}" class="p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3 flex-1">
                <svg class="drag-handle w-5 h-5 text-gray-400 cursor-move hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                </svg>
                
                <div class="flex-1">
                    <div class="font-medium text-gray-900">{{ $item->title }}</div>
                    <div class="text-sm text-gray-500">
                        @if($item->type === 'page')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Page</span>
                        @elseif($item->type === 'category')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Category</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Custom</span>
                            <span class="ml-2">{{ $item->url }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <button onclick="toggleEdit({{ $item->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Edit
                </button>
                <button onclick="deleteItem({{ $item->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Mode -->
    <div id="edit-{{ $item->id }}" class="p-4 bg-gray-50 hidden border-t border-gray-200">
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" id="title-{{ $item->id }}" value="{{ $item->title }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            @if($item->type === 'custom')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                <input type="text" id="url-{{ $item->id }}" value="{{ $item->url }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            @else
            <input type="hidden" id="url-{{ $item->id }}" value="{{ $item->url }}">
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                <select id="target-{{ $item->id }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="_self" {{ $item->target === '_self' ? 'selected' : '' }}>Same Window</option>
                    <option value="_blank" {{ $item->target === '_blank' ? 'selected' : '' }}>New Window</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button onclick="toggleEdit({{ $item->id }})" class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-white">
                    Cancel
                </button>
                <button onclick="updateItem({{ $item->id }})" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                    Save
                </button>
            </div>
        </div>
    </div>

    <!-- Nested Children Container -->
    @if($item->children && $item->children->count())
    <div class="nested-children ml-8 mt-2 space-y-2 border-l-2 border-gray-200 pl-4">
        @foreach($item->children as $child)
            @include('admin.menus.partials.menu-item', ['item' => $child])
        @endforeach
    </div>
    @else
    <div class="nested-children ml-8 mt-2 space-y-2 border-l-2 border-gray-200 pl-4 min-h-[20px]" style="display: none;"></div>
    @endif
</div>
