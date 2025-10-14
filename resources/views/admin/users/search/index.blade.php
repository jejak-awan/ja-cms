@extends('admin.layouts.admin')

@section('title', 'Advanced User Search')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Advanced User Search</h2>
            <p class="text-sm text-gray-600 mt-1">Search and filter users with advanced criteria</p>
        </div>
        <div class="flex space-x-3">
            <button id="save-search-btn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
                Save Search
            </button>
            <button id="load-saved-btn" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Load Saved
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">With Profile</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['with_profile'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('admin.users.search') }}" id="search-form">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Search Term -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Term</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Search by name, email, phone, location, website, or bio...">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Role Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Location Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <select name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Locations</option>
                        @foreach($locations as $location)
                            <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                {{ $location }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Created From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Created To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Last Login Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Login From</label>
                    <input type="date" name="last_login_from" value="{{ request('last_login_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Login To</label>
                    <input type="date" name="last_login_to" value="{{ request('last_login_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Profile Filters -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Has Profile</label>
                    <select name="has_profile" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Users</option>
                        <option value="yes" {{ request('has_profile') == 'yes' ? 'selected' : '' }}>With Profile</option>
                        <option value="no" {{ request('has_profile') == 'no' ? 'selected' : '' }}>Without Profile</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Verified</label>
                    <select name="email_verified" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Users</option>
                        <option value="yes" {{ request('email_verified') == 'yes' ? 'selected' : '' }}>Verified</option>
                        <option value="no" {{ request('email_verified') == 'no' ? 'selected' : '' }}>Not Verified</option>
                    </select>
                </div>

                <!-- Sorting -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="role" {{ request('sort_by') == 'role' ? 'selected' : '' }}>Role</option>
                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                        <option value="last_login_at" {{ request('sort_by') == 'last_login_at' ? 'selected' : '' }}>Last Login</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <select name="sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between">
                <div class="flex space-x-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                    <a href="{{ route('admin.users.search') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Clear
                    </a>
                </div>
                <div class="flex space-x-3">
                    <button type="button" id="export-results-btn" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Results
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    @if(request()->hasAny(['search', 'role', 'status', 'location', 'date_from', 'date_to', 'last_login_from', 'last_login_to', 'has_profile', 'email_verified']))
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Search Results</h3>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">{{ $users->total() }} users found</span>
                    <div class="flex space-x-2">
                        <button id="select-all-results" class="text-sm text-blue-600 hover:text-blue-800">Select All</button>
                        <button id="clear-selection-results" class="text-sm text-gray-600 hover:text-gray-800">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all-checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="user-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="{{ $user->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($user->avatar)
                                        <img class="h-10 w-10 rounded-full" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 
                                   ($user->status == 'inactive' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->location ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.users.profile.show', $user->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="{{ route('admin.users.profile.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47.881-6.084 2.33M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
        </div>
        @endif
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Save Search functionality
    document.getElementById('save-search-btn').addEventListener('click', function() {
        const searchName = prompt('Enter a name for this search:');
        if (searchName) {
            const formData = new FormData();
            formData.append('name', searchName);
            formData.append('filters', JSON.stringify({
                search: '{{ request("search") }}',
                role: '{{ request("role") }}',
                status: '{{ request("status") }}',
                location: '{{ request("location") }}',
                date_from: '{{ request("date_from") }}',
                date_to: '{{ request("date_to") }}',
                last_login_from: '{{ request("last_login_from") }}',
                last_login_to: '{{ request("last_login_to") }}',
                has_profile: '{{ request("has_profile") }}',
                email_verified: '{{ request("email_verified") }}',
                sort_by: '{{ request("sort_by") }}',
                sort_order: '{{ request("sort_order") }}'
            }));

            fetch('{{ route("admin.users.search.save") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Search saved successfully!');
                } else {
                    alert('Error saving search: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the search.');
            });
        }
    });

    // Load Saved Searches
    document.getElementById('load-saved-btn').addEventListener('click', function() {
        fetch('{{ route("admin.users.search.saved") }}')
        .then(response => response.json())
        .then(data => {
            if (data.searches && data.searches.length > 0) {
                let options = 'Select a saved search:\n';
                data.searches.forEach((search, index) => {
                    options += `${index + 1}. ${search.name} (${search.created_at})\n`;
                });
                
                const choice = prompt(options);
                if (choice && !isNaN(choice) && choice > 0 && choice <= data.searches.length) {
                    const selectedSearch = data.searches[choice - 1];
                    // Apply the saved search filters
                    Object.keys(selectedSearch.filters).forEach(key => {
                        const element = document.querySelector(`[name="${key}"]`);
                        if (element) {
                            element.value = selectedSearch.filters[key];
                        }
                    });
                    // Submit the form
                    document.getElementById('search-form').submit();
                }
            } else {
                alert('No saved searches found.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading saved searches.');
        });
    });

    // Export Results
    document.getElementById('export-results-btn').addEventListener('click', function() {
        const format = prompt('Export format (csv/json):', 'csv');
        if (format && ['csv', 'json'].includes(format.toLowerCase())) {
            const form = document.getElementById('search-form');
            const formData = new FormData(form);
            formData.append('format', format.toLowerCase());
            formData.append('fields', JSON.stringify(['id', 'name', 'email', 'role', 'status', 'location', 'created_at', 'last_login_at']));

            // Create a temporary form for download
            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = '{{ route("admin.users.search.export") }}';
            tempForm.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            tempForm.appendChild(csrfInput);

            // Add form data
            for (let [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                tempForm.appendChild(input);
            }

            document.body.appendChild(tempForm);
            tempForm.submit();
            document.body.removeChild(tempForm);
        }
    });

    // Select All functionality
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const selectAllResults = document.getElementById('select-all-results');
    const clearSelectionResults = document.getElementById('clear-selection-results');

    if (selectAllCheckbox && userCheckboxes.length > 0) {
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        selectAllResults.addEventListener('click', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            selectAllCheckbox.checked = true;
        });

        clearSelectionResults.addEventListener('click', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            selectAllCheckbox.checked = false;
        });
    }
});
</script>
@endpush
@endsection
