{{-- Featured Section Component --}}
<section class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        @if(isset($title) || isset($description))
            <div class="text-center mb-10">
                @if(isset($title))
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ $title }}
                    </h2>
                @endif
                @if(isset($description))
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ $description }}
                    </p>
                @endif
            </div>
        @endif

        {{-- Posts Grid --}}
        @if(isset($posts) && $posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <x-public.post-card :$post />
                @endforeach
            </div>

            {{-- View All Link --}}
            @if(isset($viewAllRoute))
                <div class="mt-10 text-center">
                    <a href="{{ $viewAllRoute }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                        View All Posts
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <p class="text-gray-600 dark:text-gray-400">No posts available at the moment.</p>
            </div>
        @endif
    </div>
</section>
