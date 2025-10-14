@extends('admin.layouts.admin')

@section('title', 'Edit Role')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Role</h2>
            <p class="text-sm text-gray-600 mt-1">Update role details and permissions</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Role Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="e.g., Content Manager" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $role->slug) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                           placeholder="Auto-generated from name" readonly>
                    <p class="mt-1 text-xs text-gray-500">Auto-generated from role name</p>
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Describe the role's responsibilities...">{{ old('description', $role->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Permissions Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions</h3>
            <p class="text-sm text-gray-600 mb-6">Select the permissions that this role should have</p>

            @if($permissions->count() > 0)
                @foreach($permissions as $group => $groupPermissions)
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-800 mb-3 capitalize">{{ $group ?? 'General' }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($groupPermissions as $permission)
                        <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                   class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ in_array($permission->id, old('permissions', $role->permissions ? $role->permissions->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</div>
                                @if($permission->description)
                                    <div class="text-xs text-gray-500 mt-1">{{ $permission->description }}</div>
                                @endif
                                <div class="text-xs text-gray-400 mt-1">{{ $permission->name }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No permissions available</h3>
                    <p class="mt-1 text-sm text-gray-500">Create some permissions first.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Create Permission
                        </a>
                    </div>
                </div>
            @endif

            @error('permissions')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Role Usage Info -->
        <div class="bg-blue-50 rounded-lg p-6">
            <h4 class="text-md font-medium text-blue-900 mb-3">Role Usage</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <h5 class="font-medium text-blue-800 mb-2">Users with this Role</h5>
                    <p class="text-blue-700">{{ \App\Modules\User\Models\User::where('role', $role->name)->count() }} users</p>
                </div>
                <div>
                    <h5 class="font-medium text-blue-800 mb-2">Assigned Permissions</h5>
                    <p class="text-blue-700">{{ $role->permissions ? $role->permissions->count() : 0 }} permissions</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Update Role
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        slugInput.value = slug;
    });
});
</script>
@endpush
@endsection
