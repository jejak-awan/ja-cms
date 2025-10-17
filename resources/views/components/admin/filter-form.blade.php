{{-- Filter Form Component --}}
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @if(isset($filters))
                @foreach($filters as $filter)
                    @if($filter['type'] === 'text')
                        <x-admin.input-field
                            :name="$filter['name']"
                            :placeholder="$filter['placeholder'] ?? ''"
                            :value="request($filter['name'])"
                        />
                    @elseif($filter['type'] === 'select')
                        <x-admin.select-field
                            :name="$filter['name']"
                            :options="$filter['options'] ?? []"
                            :placeholder="$filter['placeholder'] ?? 'Select...'"
                            :selected="request($filter['name'])"
                        />
                    @elseif($filter['type'] === 'date')
                        <x-admin.input-field
                            type="date"
                            :name="$filter['name']"
                            :value="request($filter['name'])"
                        />
                    @endif
                @endforeach
            @else
                {{ $slot }}
            @endif
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                {{ __('admin.common.search') }}
            </button>
            <a href="{{ request()->url() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('admin.common.reset') }}
            </a>
        </div>
    </form>
</div>
