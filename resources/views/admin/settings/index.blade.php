@extends('admin.layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Site Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Manage your site configuration</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm">
        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('general')" class="tab-btn active px-6 py-4 text-sm font-medium border-b-2 border-blue-500 text-blue-600">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    General
                </button>
                <button onclick="showTab('seo')" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    SEO
                </button>
                <button onclick="showTab('social')" class="tab-btn px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                    </svg>
                    Social Media
                </button>
            </nav>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- General Tab -->
            <div id="general-tab" class="tab-content">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                        <input type="text" name="settings[site_name]" value="{{ $settings['site_name'] ?? 'Laravel CMS' }}" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Tagline</label>
                        <input type="text" name="settings[site_tagline]" value="{{ $settings['site_tagline'] ?? 'Modern Content Management System' }}" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                        <textarea name="settings[site_description]" rows="3" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                            <input type="email" name="settings[site_email]" value="{{ $settings['site_email'] ?? 'admin@example.com' }}" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                            <input type="text" name="settings[site_phone]" value="{{ $settings['site_phone'] ?? '' }}" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Address</label>
                        <textarea name="settings[site_address]" rows="2" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ $settings['site_address'] ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Items Per Page</label>
                            <input type="number" name="settings[site_per_page]" value="{{ $settings['site_per_page'] ?? '10' }}" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select name="settings[site_timezone]" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="UTC" {{ ($settings['site_timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="Asia/Jakarta" {{ ($settings['site_timezone'] ?? '') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                                <option value="America/New_York" {{ ($settings['site_timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New York</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Tab -->
            <div id="seo-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="settings[seo_title]" value="{{ $settings['seo_title'] ?? '' }}" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Leave blank to use site name</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="settings[seo_description]" rows="3" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ $settings['seo_description'] ?? '' }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input type="text" name="settings[seo_keywords]" value="{{ $settings['seo_keywords'] ?? '' }}" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Comma separated keywords</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                        <input type="text" name="settings[seo_google_analytics]" value="{{ $settings['seo_google_analytics'] ?? '' }}" 
                            placeholder="G-XXXXXXXXXX"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Google Search Console</label>
                        <input type="text" name="settings[seo_google_console]" value="{{ $settings['seo_google_console'] ?? '' }}" 
                            placeholder="Verification code"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Robots Meta Tag</label>
                        <select name="settings[seo_robots]" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="index,follow" {{ ($settings['seo_robots'] ?? 'index,follow') == 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                            <option value="noindex,follow" {{ ($settings['seo_robots'] ?? '') == 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                            <option value="index,nofollow" {{ ($settings['seo_robots'] ?? '') == 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                            <option value="noindex,nofollow" {{ ($settings['seo_robots'] ?? '') == 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Social Media Tab -->
            <div id="social-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Facebook Page URL</label>
                        <input type="url" name="settings[social_facebook]" value="{{ $settings['social_facebook'] ?? '' }}" 
                            placeholder="https://facebook.com/yourpage"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Twitter/X Username</label>
                        <input type="text" name="settings[social_twitter]" value="{{ $settings['social_twitter'] ?? '' }}" 
                            placeholder="@username"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instagram Username</label>
                        <input type="text" name="settings[social_instagram]" value="{{ $settings['social_instagram'] ?? '' }}" 
                            placeholder="@username"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn Profile URL</label>
                        <input type="url" name="settings[social_linkedin]" value="{{ $settings['social_linkedin'] ?? '' }}" 
                            placeholder="https://linkedin.com/in/yourprofile"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Channel URL</label>
                        <input type="url" name="settings[social_youtube]" value="{{ $settings['social_youtube'] ?? '' }}" 
                            placeholder="https://youtube.com/c/yourchannel"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">GitHub Profile URL</label>
                        <input type="url" name="settings[social_github]" value="{{ $settings['social_github'] ?? '' }}" 
                            placeholder="https://github.com/yourusername"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 pt-6 border-t">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    event.target.closest('.tab-btn').classList.add('active', 'border-blue-500', 'text-blue-600');
    event.target.closest('.tab-btn').classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endpush
@endsection
