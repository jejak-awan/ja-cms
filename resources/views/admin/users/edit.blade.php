@extends('admin.layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <x-admin.page-header
        title="Edit User"
        subtitle="Update user information"
    >
        <x-slot name="actions">
            <x-admin.button 
                type="link" 
                :href="route('admin.users.index')" 
                variant="secondary"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </x-admin.button>
        </x-slot>
    </x-admin.page-header>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Name -->
                <x-admin.input-field
                    name="name"
                    label="Full Name"
                    type="text"
                    :value="old('name', $user->name)"
                    required
                    placeholder="Enter full name..."
                />

                <!-- Email -->
                <x-admin.input-field
                    name="email"
                    label="Email Address"
                    type="email"
                    :value="old('email', $user->email)"
                    required
                    placeholder="user@example.com"
                />

                <!-- Password -->
                <x-admin.input-field
                    name="password"
                    label="Password"
                    type="password"
                    help="Leave blank to keep current password. Minimum 8 characters if changing."
                />

                <!-- Password Confirmation -->
                <x-admin.input-field
                    name="password_confirmation"
                    label="Confirm Password"
                    type="password"
                />

                <!-- Role -->
                <x-admin.select-field
                    name="role"
                    label="Role"
                    :options="['subscriber' => 'Subscriber', 'author' => 'Author', 'editor' => 'Editor', 'admin' => 'Administrator']"
                    :selected="old('role', $user->role)"
                />

                <!-- Status -->
                <x-admin.select-field
                    name="status"
                    label="Status"
                    :options="['active' => 'Active', 'inactive' => 'Inactive', 'suspended' => 'Suspended']"
                    :selected="old('status', $user->status ?? 'active')"
                />

                <!-- Bio -->
                <x-admin.textarea-field
                    name="bio"
                    label="Bio"
                    :value="old('bio', $user->bio)"
                    rows="4"
                    placeholder="Short description about the user..."
                    help="Short description about the user"
                />
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t dark:border-gray-700">
                <x-admin.button 
                    type="link" 
                    :href="route('admin.users.index')" 
                    variant="secondary"
                >
                    Cancel
                </x-admin.button>
                <x-admin.button type="submit" variant="primary">
                    Update User
                </x-admin.button>
            </div>
        </form>
    </div>
</div>
@endsection
