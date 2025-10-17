{{-- Alpine.js Interactive Data Table Component --}}
<div 
    x-data="dataTable(@json($items), @json($columns), @json($actions ?? []))"
    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden"
>
    {{-- Search & Filter Bar --}}
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            {{-- Search Input --}}
            <div class="flex-1 relative">
                <input 
                    x-model="search"
                    type="text"
                    placeholder="Search..."
                    @input="applyFilters()"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:outline-none focus:border-blue-500"
                >
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            {{-- Display Options --}}
            <div class="flex items-center gap-2">
                <select 
                    x-model="perPage"
                    @change="applyFilters()"
                    class="px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600"
                >
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>

                {{-- Clear Filters --}}
                <button 
                    @click="clearFilters()"
                    class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                >
                    Clear
                </button>
            </div>
        </div>

        {{-- Active Filters Info --}}
        <template x-if="search">
            <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                Found <span x-text="filteredItems.length"></span> results for "<span x-text="search"></span>"
            </div>
        </template>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
            {{-- Header --}}
            <thead class="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                <tr>
                    {{-- Checkbox Header (if bulk actions) --}}
                    <template x-if="hasBulkActions">
                        <th class="px-6 py-3 w-10">
                            <input 
                                type="checkbox"
                                x-model="selectAll"
                                @change="toggleSelectAll()"
                                class="w-4 h-4 rounded border-gray-300 text-blue-600 dark:border-gray-600"
                            >
                        </th>
                    </template>

                    {{-- Column Headers --}}
                    <template x-for="column in columns" :key="column.field">
                        <th 
                            class="px-6 py-3 text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                            @click="sortBy(column.field)"
                        >
                            <div class="flex items-center gap-2">
                                <span x-text="column.label"></span>
                                {{-- Sort Indicator --}}
                                <template x-if="sortField === column.field">
                                    <svg class="w-4 h-4" :class="{'rotate-180': sortOrder === 'desc'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m0 0l4 4m10-4v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </template>
                            </div>
                        </th>
                    </template>

                    {{-- Actions Header --}}
                    <template x-if="actions.length > 0">
                        <th class="px-6 py-3 text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </template>
                </tr>
            </thead>

            {{-- Body --}}
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <template x-for="item in paginatedItems" :key="item.id">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition" :class="{'bg-blue-50 dark:bg-blue-900/20': isSelected(item.id)}">
                        {{-- Checkbox --}}
                        <template x-if="hasBulkActions">
                            <td class="px-6 py-4 w-10">
                                <input 
                                    type="checkbox"
                                    :value="item.id"
                                    x-model="selected"
                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 dark:border-gray-600"
                                >
                            </td>
                        </template>

                        {{-- Data Cells --}}
                        <template x-for="column in columns" :key="column.field">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <span x-html="renderCell(item, column)"></span>
                            </td>
                        </template>

                        {{-- Actions --}}
                        <template x-if="actions.length > 0">
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <template x-for="action in actions" :key="action.type">
                                        <template x-if="action.type === 'link'">
                                            <a 
                                                :href="action.getUrl(item)"
                                                :title="action.title"
                                                class="inline-flex items-center gap-2 px-2 py-1 text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition"
                                            >
                                                <span x-html="getSvgIcon(action.icon)"></span>
                                                <span x-text="action.title"></span>
                                            </a>
                                        </template>

                                        <template x-if="action.type === 'button'">
                                            <button 
                                                @click="action.handler(item)"
                                                :title="action.title"
                                                class="inline-flex items-center gap-2 px-2 py-1 text-xs font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition"
                                            >
                                                <span x-html="getSvgIcon(action.icon)"></span>
                                                <span x-text="action.title"></span>
                                            </button>
                                        </template>
                                    </template>
                                </div>
                            </td>
                        </template>
                    </tr>
                </template>

                {{-- Empty State --}}
                <template x-if="paginatedItems.length === 0">
                    <tr>
                        <td :colspan="columns.length + (hasBulkActions ? 1 : 0) + (actions.length > 0 ? 1 : 0)" class="px-6 py-12 text-center">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p x-text="search ? 'No results found' : 'No data available'"></p>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <template x-if="totalPages > 1">
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Showing <span x-text="(currentPage - 1) * perPage + 1"></span> to 
                <span x-text="Math.min(currentPage * perPage, filteredItems.length)"></span> 
                of <span x-text="filteredItems.length"></span> results
            </div>

            <div class="flex items-center gap-2">
                <button 
                    @click="previousPage()"
                    :disabled="currentPage === 1"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                    Previous
                </button>

                {{-- Page Numbers --}}
                <template x-for="page in getPageNumbers()" :key="page">
                    <template x-if="page === '...'">
                        <span class="px-2 text-gray-400">...</span>
                    </template>
                    <template x-if="page !== '...'">
                        <button 
                            @click="currentPage = page"
                            :class="currentPage === page ? 'bg-blue-600 text-white' : 'border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'"
                            class="px-3 py-2 text-sm rounded-lg transition"
                        >
                            <span x-text="page"></span>
                        </button>
                    </template>
                </template>

                <button 
                    @click="nextPage()"
                    :disabled="currentPage === totalPages"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                    Next
                </button>
            </div>
        </div>
    </template>

    {{-- Bulk Actions --}}
    <template x-if="hasBulkActions && selected.length > 0">
        <div class="px-6 py-3 bg-blue-50 dark:bg-blue-900/20 border-t border-blue-200 dark:border-blue-800 flex items-center justify-between">
            <span class="text-sm text-gray-700 dark:text-gray-300">
                <span x-text="selected.length"></span> items selected
            </span>
            <div class="flex items-center gap-2">
                <button 
                    @click="clearSelection()"
                    class="px-3 py-1 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition"
                >
                    Clear Selection
                </button>
            </div>
        </div>
    </template>
</div>

<script>
function dataTable(items, columns, actions = []) {
    return {
        // Data
        allItems: items,
        columns: columns,
        actions: actions,

        // State
        search: '',
        currentPage: 1,
        perPage: 10,
        sortField: null,
        sortOrder: 'asc',
        selected: [],
        selectAll: false,

        // Computed
        get filteredItems() {
            let filtered = this.allItems;

            // Apply search
            if (this.search) {
                const searchLower = this.search.toLowerCase();
                filtered = filtered.filter(item => {
                    return this.columns.some(col => {
                        const value = item[col.field];
                        return String(value).toLowerCase().includes(searchLower);
                    });
                });
            }

            // Apply sorting
            if (this.sortField) {
                filtered.sort((a, b) => {
                    let aVal = a[this.sortField];
                    let bVal = b[this.sortField];

                    if (aVal < bVal) return this.sortOrder === 'asc' ? -1 : 1;
                    if (aVal > bVal) return this.sortOrder === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            return filtered;
        },

        get totalPages() {
            return Math.ceil(this.filteredItems.length / this.perPage);
        },

        get paginatedItems() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + parseInt(this.perPage);
            return this.filteredItems.slice(start, end);
        },

        get hasBulkActions() {
            return this.actions.some(a => a.type === 'bulk');
        },

        // Methods
        applyFilters() {
            this.currentPage = 1;
        },

        clearFilters() {
            this.search = '';
            this.sortField = null;
            this.sortOrder = 'asc';
            this.currentPage = 1;
        },

        sortBy(field) {
            if (this.sortField === field) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortOrder = 'asc';
            }
        },

        previousPage() {
            if (this.currentPage > 1) this.currentPage--;
        },

        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },

        getPageNumbers() {
            const pages = [];
            const maxPages = 5;
            const halfWindow = Math.floor(maxPages / 2);

            let start = Math.max(1, this.currentPage - halfWindow);
            let end = Math.min(this.totalPages, start + maxPages - 1);

            if (end - start < maxPages - 1) {
                start = Math.max(1, end - maxPages + 1);
            }

            if (start > 1) {
                pages.push(1);
                if (start > 2) pages.push('...');
            }

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            if (end < this.totalPages) {
                if (end < this.totalPages - 1) pages.push('...');
                pages.push(this.totalPages);
            }

            return pages;
        },

        renderCell(item, column) {
            // Data is pre-rendered from Blade, just return it as-is
            const value = item[column.field];
            if (typeof value === 'string') {
                return value;
            }
            if (typeof value === 'function') {
                return value(item);
            }
            return String(value || '');
        },

        toggleSelectAll() {
            if (this.selectAll) {
                this.selected = this.paginatedItems.map(i => i.id);
            } else {
                this.selected = [];
            }
        },

        isSelected(id) {
            return this.selected.includes(id);
        },

        clearSelection() {
            this.selected = [];
            this.selectAll = false;
        },

        getSvgIcon(icon) {
            if (typeof icon === 'string') {
                if (icon === 'edit') {
                    return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>';
                } else if (icon === 'delete') {
                    return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
                }
            }
            return icon;
        }
    };
}
</script>
