@extends('admin.layouts.admin')

@section('title', 'User Import/Export')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">User Import/Export</h2>
            <p class="text-sm text-gray-600 mt-1">Import users from files or export user data</p>
        </div>
    </div>

    <!-- Export Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center mb-4">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Export Users</h3>
                <p class="text-sm text-gray-600">Download user data in various formats</p>
            </div>
        </div>

        <form id="export-form" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                    <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="csv">CSV</option>
                        <option value="json">JSON</option>
                        <option value="xlsx">Excel (XLSX)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role Filter</label>
                    <select name="role_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Roles</option>
                        @foreach(\App\Modules\User\Models\Role::all() as $role)
                            <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                    <select name="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fields to Export</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="id" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">ID</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="name" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Name</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="email" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Email</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="role" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Role</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="status" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Status</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="phone" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Phone</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="location" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Location</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="website" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Website</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="bio" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Bio</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="created_at" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Created At</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="fields[]" value="last_login_at" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Last Login</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Users
                </button>
            </div>
        </form>
    </div>

    <!-- Import Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Import Users</h3>
                <p class="text-sm text-gray-600">Upload a file to import users</p>
            </div>
        </div>

        <form id="import-form" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Import File</label>
                    <input type="file" name="file" accept=".csv,.txt,.xlsx,.xls" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <p class="mt-1 text-xs text-gray-500">CSV, TXT, XLSX, XLS files up to 10MB</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="update_existing" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Update existing users</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="send_welcome_email" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Send welcome email</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('admin.users.import-export.template') }}?format=csv" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Template
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    Import Users
                </button>
            </div>
        </form>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 rounded-lg p-6">
        <h4 class="text-md font-medium text-blue-900 mb-3">Import Instructions</h4>
        <div class="text-sm text-blue-800 space-y-2">
            <p><strong>Required Fields:</strong> name, email</p>
            <p><strong>Optional Fields:</strong> role, status, phone, location, website, bio</p>
            <p><strong>Valid Roles:</strong> admin, editor, author, contributor, subscriber</p>
            <p><strong>Valid Status:</strong> active, inactive, suspended</p>
            <p><strong>File Format:</strong> CSV with header row, UTF-8 encoding</p>
            <p><strong>Max File Size:</strong> 10MB</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Export form submission
document.getElementById('export-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = '{{ route("admin.users.import-export.export") }}';
    
    // Create a temporary form for download
    const tempForm = document.createElement('form');
    tempForm.method = 'POST';
    tempForm.action = url;
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
});

// Import form submission
document.getElementById('import-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Importing...';
    
    fetch('{{ route("admin.users.import-export.import") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Import completed successfully!\n\nTotal: ${data.results.total}\nCreated: ${data.results.created}\nUpdated: ${data.results.updated}\nSkipped: ${data.results.skipped}\nErrors: ${data.results.errors.length}`);
            
            if (data.results.errors.length > 0) {
                console.log('Import errors:', data.results.errors);
            }
            
            // Reset form
            this.reset();
        } else {
            alert('Import failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during import.');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});
</script>
@endpush
@endsection
