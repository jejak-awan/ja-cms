@extends('admin.layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">My Profile</h2>
            <p class="text-sm text-gray-600 mt-1">Manage your account information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Profile
            </a>
            <a href="{{ route('admin.profile.change-password') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                Change Password
            </a>
        </div>
    </div>

    <!-- Profile Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-start space-x-6">
            <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full overflow-hidden bg-gray-100">
                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 text-white text-2xl font-bold">
                            {{ $user->initials }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                <p class="text-lg text-gray-600">{{ $user->email }}</p>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->is_verified)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Verified
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                    <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                    <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                    <dd class="text-sm text-gray-900">{{ ucfirst($user->role) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm text-gray-900">{{ ucfirst($user->status) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                    <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Website</dt>
                    <dd class="text-sm text-gray-900">
                        @if($user->website)
                            <a href="{{ $user->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $user->website }}</a>
                        @else
                            Not provided
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                    <dd class="text-sm text-gray-900">{{ $user->location ?? 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                    <dd class="text-sm text-gray-900">{{ $user->birth_date ? $user->birth_date->format('M d, Y') : 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                    <dd class="text-sm text-gray-900">{{ $user->gender ? ucfirst($user->gender) : 'Not specified' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Bio -->
    @if($user->bio)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">About</h3>
        <p class="text-sm text-gray-700 leading-relaxed">{{ $user->bio }}</p>
    </div>
    @endif

    <!-- Social Links -->
    @if($user->social_links && count(array_filter($user->social_links)))
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Social Links</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($user->social_links as $platform => $url)
                @if($url)
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($platform === 'facebook')
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        @elseif($platform === 'twitter')
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        @elseif($platform === 'linkedin')
                            <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        @elseif($platform === 'instagram')
                            <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.014 5.367 18.647.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.297-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.807.875 1.297 2.026 1.297 3.323s-.49 2.448-1.297 3.323c-.875.807-2.026 1.297-3.323 1.297zm7.83-9.281c-.49 0-.875-.385-.875-.875s.385-.875.875-.875.875.385.875.875-.385.875-.875.875zm-3.323 1.297c-1.297 0-2.448.49-3.323 1.297-.807.875-1.297 2.026-1.297 3.323s.49 2.448 1.297 3.323c.875.807 2.026 1.297 3.323 1.297s2.448-.49 3.323-1.297c.807-.875 1.297-2.026 1.297-3.323s-.49-2.448-1.297-3.323c-.875-.807-2.026-1.297-3.323-1.297z"/>
                            </svg>
                        @elseif($platform === 'github')
                            <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 capitalize">{{ $platform }}</p>
                        <a href="{{ $url }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 truncate">{{ $url }}</a>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Activity Information -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Information</h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                <dd class="text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Last Login IP</dt>
                <dd class="text-sm text-gray-900">{{ $user->last_login_ip ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                <dd class="text-sm text-gray-900">{{ $user->is_verified ? 'Yes' : 'No' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Account Created</dt>
                <dd class="text-sm text-gray-900">{{ $user->created_at->diffForHumans() }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
