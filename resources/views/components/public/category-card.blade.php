{{-- Category Card Component --}}
<a href="{{ route('public.categories.show', $category->slug) }}" class="block group">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
        {{-- Category Header --}}
        <div class="relative h-32 bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-700 dark:to-blue-800 overflow-hidden">
            {{-- Icon/Background Pattern --}}
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100">
                    <circle cx="20" cy="20" r="15" fill="currentColor" opacity="0.1"/>
                    <circle cx="80" cy="30" r="20" fill="currentColor" opacity="0.1"/>
                    <circle cx="50" cy="70" r="18" fill="currentColor" opacity="0.1"/>
                </svg>
            </div>

            {{-- Category Icon --}}
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-16 h-16 text-white opacity-80 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                </svg>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-4">
            {{-- Category Name --}}
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                {{ $category->name }}
            </h3>

            {{-- Description --}}
            @if($category->description)
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                    {{ Str::limit($category->description, 80) }}
                </p>
            @endif

            {{-- Article Count --}}
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    {{ $category->articles_count ?? 0 }} 
                    {{ $category->articles_count === 1 ? 'Article' : 'Articles' }}
                </span>
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>
    </div>
</a>
