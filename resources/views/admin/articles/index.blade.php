@extends('admin.layouts.admin')

@section('title', __('admin.articles.title'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.articles.title') }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('admin.articles.manage_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('admin.articles.create') }}
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="{{ __('admin.articles.search_placeholder') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Category Filter -->
                <div>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">{{ __('admin.common.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">{{ __('admin.common.all_status') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Author Filter -->
                <div>
                    <select name="author" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">{{ __('admin.common.all_authors') }}</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        {{ __('admin.common.apply_filters') }}
                    </button>
                    <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                        {{ __('admin.common.reset') }}
                    </a>
                </div>

                <!-- Bulk Actions -->
                <div class="flex items-center space-x-2">
                    <select id="bulkAction" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Bulk Actions</option>
                        <option value="publish">Publish Selected</option>
                        <option value="draft">Move to Draft</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="button" onclick="applyBulkAction()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition">
                        Apply
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Articles Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($articles->count() > 0)
        <form id="bulkForm" method="POST" action="{{ route('admin.articles.bulk-action') }}">
            @csrf
            <input type="hidden" name="action" id="bulkActionInput">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600" onclick="sortBy('title_id')">
                                {{ __('admin.articles.title_label') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('admin.articles.category_label') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('admin.articles.author') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600" onclick="sortBy('status')">
                                {{ __('admin.common.status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600" onclick="sortBy('created_at')">
                                {{ __('admin.common.created_at') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('admin.common.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($articles as $article)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($article->featured_image)
                                        <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title_id }}" class="w-10 h-10 rounded object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                            {{ Str::limit($article->title_id, 50) }}
                                        </a>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($article->excerpt_id ?? '', 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">
                                    {{ $article->category?->name ?? __('admin.common.uncategorized') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $article->user?->name ?? __('admin.common.unknown') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = match($article->status) {
                                        'published' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                        'draft' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                    };
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $statusClass }}">
                                    {{ __('admin.articles.status.' . $article->status) }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $article->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition" title="{{ __('admin.common.edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                        <button 
                                            type="button"
                                            onclick="confirmDelete({{ $article->id }}, '{{ addslashes($article->title_id) }}')"
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

        <!-- Pagination -->
        <div class="px-6 py-4 border-t dark:border-gray-700">
            {{ $articles->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('admin.common.no_data_found') }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.articles.empty.no_articles') }}</p>
            <div class="mt-6">
                <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('admin.articles.index.btn_create') }}
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Select all checkbox
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.article-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

// Bulk actions
function applyBulkAction() {
    const action = document.getElementById('bulkAction').value;
    if (!action) {
        alert('Please select an action');
        return;
    }

    const checkedBoxes = document.querySelectorAll('.article-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one article');
        return;
    }

    if (action === 'delete') {
        if (!confirm(`Are you sure you want to delete ${checkedBoxes.length} article(s)?`)) {
            return;
        }
    }

    document.getElementById('bulkActionInput').value = action;
    document.getElementById('bulkForm').submit();
}

function confirmDelete(id, title) {
    document.getElementById('deleteArticleMessage').textContent = 
        `{{ __('admin.articles.confirm_delete') }}: "${title}"?`;
    document.getElementById('deleteArticleForm').action = `/admin/articles/${id}`;
    openModal('deleteArticleModal');
}
</script>
@endpush

{{-- Delete Confirmation Modal --}}
<x-admin.modal id="deleteArticleModal" title="{{ __('admin.common.confirm') }} {{ __('admin.common.delete') }}?">
    <p class="text-gray-600 dark:text-gray-400" id="deleteArticleMessage">
        {{ __('admin.articles.confirm_delete') }}
    </p>
    
    <x-slot name="footer">
        <button 
            type="button"
            onclick="closeModal('deleteArticleModal')"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition"
        >
            {{ __('admin.common.cancel') }}
        </button>
        <form id="deleteArticleForm" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button 
                type="submit"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition"
            >
                {{ __('admin.common.delete') }}
            </button>
        </form>
    </x-slot>
</x-admin.modal>

@endsection
