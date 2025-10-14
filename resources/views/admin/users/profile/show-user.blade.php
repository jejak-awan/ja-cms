@extends('admin.layouts.admin')

@section('title', 'User Profile')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
            <p class="text-sm text-gray-600">{{ $user->email }}</p>
            <p class="text-xs text-gray-500 mt-1">Role: {{ $user->role }} | Status: {{ $user->status }}</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div>
            <h4 class="font-semibold text-gray-800 mb-2">Profile Info</h4>
            <ul class="text-sm text-gray-700 space-y-2">
                <li><strong>Phone:</strong> {{ $user->phone ?? '-' }}</li>
                <li><strong>Website:</strong> {{ $user->website ?? '-' }}</li>
                <li><strong>Location:</strong> {{ $user->location ?? '-' }}</li>
                <li><strong>Birth Date:</strong> {{ $user->birth_date ?? '-' }}</li>
                <li><strong>Gender:</strong> {{ $user->gender ?? '-' }}</li>
                <li><strong>Bio:</strong> {{ $user->bio ?? '-' }}</li>
            </ul>
        </div>
        <div>
            <h4 class="font-semibold text-gray-800 mb-2">Social Links</h4>
            @if($user->social_links)
                <ul class="text-sm text-gray-700 space-y-2">
                    @foreach($user->social_links as $platform => $link)
                        <li><strong>{{ ucfirst($platform) }}:</strong> <a href="{{ $link }}" target="_blank" class="text-blue-600 hover:underline">{{ $link }}</a></li>
                    @endforeach
                </ul>
            @else
                <p class="text-xs text-gray-500">No social links provided.</p>
            @endif
        </div>
    </div>
    <div class="mt-8">
        <a href="{{ route('admin.users.profile.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Edit Profile</a>
    </div>
</div>
@endsection
