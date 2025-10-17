@extends('admin.layouts.admin')

@section('title', __('Translation Overrides'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('Translation Overrides') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Welcome back, Admin') }}
            </p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.translations.statistics') }}" 
               class="btn btn-secondary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="ml-1">{{ __('Statistics') }}</span>
            </a>
            <a href="{{ route('admin.translations.missing') }}" 
               class="btn btn-warning btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="ml-1">{{ __('Missing') }}</span>
            </a>
            <a href="{{ route('admin.translations.create') }}" 
               class="btn btn-primary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="ml-1">{{ __('Add Override') }}</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    <!-- Filters -->
    <div class="card">
        <form method="GET" action="{{ route('admin.translations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Search') }}
                </label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="{{ __('Key or value...') }}"
                       class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Language') }}
                </label>
                <select name="locale" class="form-select">
                    <option value="">{{ __('All Languages') }}</option>
                    @foreach(\App\Modules\Language\Models\Language::active() as $locale)
                        <option value="{{ $locale->code }}" {{ request('locale') == $locale->code ? 'selected' : '' }}>
                            {{ $locale->native_name }} ({{ $locale->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Domain') }}
                </label>
                <input type="text" 
                       name="domain" 
                       value="{{ request('domain') }}" 
                       placeholder="{{ __('messages, validation...') }}"
                       class="form-input">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    {{ __('Filter') }}
                </button>
                <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary">
                    {{ __('Reset') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Overrides Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            {{ __('Key') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            {{ __('Domain') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            {{ __('Language') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            {{ __('Value') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            {{ __('Status') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($overrides as $override)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">
                                    {{ $override->key }}
                                </code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge badge-gray">
                                    {{ $override->domain }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge badge-info">
                                    {{ strtoupper($override->locale) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-md truncate text-sm text-gray-900 dark:text-gray-100">
                                    {{ Str::limit($override->value, 80) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" 
                                      action="{{ route('admin.translations.toggle', $override) }}"
                                      class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="badge {{ $override->is_active ? 'badge-success' : 'badge-gray' }}">
                                        {{ $override->is_active ? __('Active') : __('Inactive') }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.translations.edit', $override) }}" 
                                       class="btn btn-sm btn-secondary">
                                        {{ __('Edit') }}
                                    </a>
                                    <form method="POST" 
                                          action="{{ route('admin.translations.destroy', $override) }}"
                                          onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                                <p class="text-lg font-medium">{{ __('No translation overrides found') }}</p>
                                <p class="mt-1">{{ __('Create your first override to customize translations') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($overrides->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $overrides->links() }}
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 flex justify-between items-center">
        <form method="POST" 
              action="{{ route('admin.translations.clear-cache') }}"
              onsubmit="return confirm('{{ __('Clear all translation caches?') }}')">
            @csrf
            <button type="submit" class="btn btn-secondary">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                {{ __('Clear Translation Cache') }}
            </button>
        </form>

        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Total: :count overrides', ['count' => $overrides->total()]) }}
        </div>
    </div>
</div>
@endsection
