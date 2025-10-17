{{-- Post Card Component --}}
<article class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden group">
    {{-- Featured Image --}}
    <div class="relative h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
        @if($post->featured_image)
            <img 
                src="{{ Storage::url($post->featured_image) }}" 
                alt="{{ $post->title_id }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            >
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-4">
        {{-- Category Badge --}}
        @if($post->category)
            <a href="{{ route('public.categories.show', $post->category->slug) }}" class="inline-block">
                <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                    {{ $post->category->name }}
                </span>
            </a>
        @endif

        {{-- Title --}}
        <h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
            <a href="{{ route('public.articles.show', $post->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                {{ app()->getLocale() === 'id' ? $post->title_id : $post->title_en }}
            </a>
        </h3>

        {{-- Excerpt --}}
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
            {{ app()->getLocale() === 'id' ? Str::limit($post->excerpt_id ?? '', 100) : Str::limit($post->excerpt_en ?? '', 100) }}
        </p>

        {{-- Metadata --}}
        <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-2">
                @if($post->user)
                    <span>By {{ $post->user->name }}</span>
                @endif
            </div>
            <time datetime="{{ $post->created_at->toIso8601String() }}">
                {{ $post->created_at->format('M d, Y') }}
            </time>
        </div>

        {{-- Read More Button --}}
        <a href="{{ route('public.articles.show', $post->slug) }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
            Read More
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</article>
