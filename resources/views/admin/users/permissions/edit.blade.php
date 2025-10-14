@extends('admin.layouts.admin')

@section('title', 'Edit Permission')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Permission</h2>
            <p class="text-sm text-gray-600 mt-1">Update permission details</p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Permission Information</h3>
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Permission Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="e.g., manage_articles" required>
                    <p class="mt-1 text-xs text-gray-500">Use snake_case format (e.g., manage_articles, view_dashboard)</p>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">Display Name *</label>
                    <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $permission->display_name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('display_name') border-red-500 @enderror"
                           placeholder="e.g., Manage Articles" required>
                    <p class="mt-1 text-xs text-gray-500">Human-readable name for the permission</p>
                    @error('display_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Describe what this permission allows...">{{ old('description', $permission->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700 mb-2">Group</label>
                    <select id="group" name="group" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('group') border-red-500 @enderror">
                        <option value="">Select a group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group }}" {{ old('group', $permission->group) == $group ? 'selected' : '' }}>
                                {{ ucfirst($group) }}
                            </option>
                        @endforeach
                        <option value="custom" {{ old('group', $permission->group) == 'custom' ? 'selected' : '' }}>Custom Group</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Group permissions for better organization</p>
                    @error('group')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="custom-group" class="{{ old('group', $permission->group) == 'custom' ? '' : 'hidden' }}">
                    <label for="custom_group" class="block text-sm font-medium text-gray-700 mb-2">Custom Group Name</label>
                    <input type="text" id="custom_group" name="custom_group" value="{{ old('custom_group') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Content Management">
                </div>
            </div>
        </div>

        <!-- Permission Usage Info -->
        <div class="bg-blue-50 rounded-lg p-6">
            <h4 class="text-md font-medium text-blue-900 mb-3">Permission Usage</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <h5 class="font-medium text-blue-800 mb-2">Assigned to Roles</h5>
                    <p class="text-blue-700">{{ $permission->roles()->count() }} roles</p>
                </div>
                <div>
                    <h5 class="font-medium text-blue-800 mb-2">Assigned to Users</h5>
                    <p class="text-blue-700">{{ $permission->users()->count() }} users</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Update Permission
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const groupSelect = document.getElementById('group');
    const customGroupDiv = document.getElementById('custom-group');
    const customGroupInput = document.getElementById('custom_group');
    
    groupSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customGroupDiv.classList.remove('hidden');
            customGroupInput.required = true;
        } else {
            customGroupDiv.classList.add('hidden');
            customGroupInput.required = false;
        }
    });
    
    // Auto-generate display name from name
    const nameInput = document.getElementById('name');
    const displayNameInput = document.getElementById('display_name');
    
    nameInput.addEventListener('input', function() {
        if (!displayNameInput.value || displayNameInput.value === '{{ $permission->display_name }}') {
            const displayName = this.value
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
            displayNameInput.value = displayName;
        }
    });
});
</script>
@endpush
@endsection
