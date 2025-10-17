# Frontend Components Documentation

This document provides comprehensive documentation for all reusable frontend (public-facing) Blade components created for the CMS application.

## Table of Contents
1. [Post Card Component](#post-card-component)
2. [Category Card Component](#category-card-component)
3. [Featured Section Component](#featured-section-component)
4. [Search Box Component](#search-box-component)
5. [Frontend Pagination Component](#frontend-pagination-component)

---

## Post Card Component

**Location:** `resources/views/components/public/post-card.blade.php`

### Purpose
Displays a single blog post in a card format with featured image, title, excerpt, category, author, and publication date.

### Properties
- `$post` (Article Model) - The post object to display

### Features
- ✅ Featured image with fallback placeholder
- ✅ Zoom effect on hover
- ✅ Multi-language support (ID/EN)
- ✅ Category badge linking to category page
- ✅ Responsive design
- ✅ Dark mode support
- ✅ Author and date metadata

### Usage Example
```blade
<x-public.post-card :post="$article" />
```

### Usage in Grid
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($posts as $post)
        <x-public.post-card :$post />
    @endforeach
</div>
```

---

## Category Card Component

**Location:** `resources/views/components/public/category-card.blade.php`

### Purpose
Displays a category card with gradient background, category name, description, and article count.

### Properties
- `$category` (Category Model) - The category object to display

### Features
- ✅ Gradient background with pattern
- ✅ Animated icon on hover
- ✅ Article count display
- ✅ Smooth link transition
- ✅ Responsive design
- ✅ Dark mode support
- ✅ SEO-friendly links

### Usage Example
```blade
<x-public.category-card :category="$category" />
```

### Usage in Grid
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($categories as $category)
        <x-public.category-card :$category />
    @endforeach
</div>
```

---

## Featured Section Component

**Location:** `resources/views/components/public/featured-section.blade.php`

### Purpose
Displays a collection of featured posts in a grid layout with an optional title, description, and "View All" link.

### Properties
- `$posts` (Collection) - Collection of posts to display
- `$title` (String, Optional) - Section title
- `$description` (String, Optional) - Section description
- `$viewAllRoute` (String, Optional) - Route to view all posts

### Features
- ✅ Grid layout with responsive columns
- ✅ Uses post-card component internally
- ✅ Optional section header
- ✅ View all link
- ✅ Empty state handling
- ✅ Dark mode support

### Usage Example
```blade
<x-public.featured-section
    :posts="$featuredPosts"
    title="Featured Articles"
    description="Read our latest and most popular articles"
    viewAllRoute="{{ route('public.articles.index') }}"
/>
```

### Alternative Usage (Minimal)
```blade
<x-public.featured-section :posts="$latestPosts" />
```

---

## Search Box Component

**Location:** `resources/views/components/public/search-box.blade.php`

### Purpose
Provides a search input field for searching articles across the public site.

### Properties
- `$placeholder` (String, Optional) - Search input placeholder text (default: "Search articles...")
- `$showTips` (Boolean, Optional) - Show search tips below input (default: false)

### Features
- ✅ Integrated search icon
- ✅ Responsive design
- ✅ Maintains search query in input
- ✅ Dark mode support
- ✅ Optional search tips
- ✅ Accessible with ARIA labels
- ✅ Submits to public.search route

### Usage Example
```blade
<x-public.search-box />
```

### Usage with Custom Placeholder
```blade
<x-public.search-box 
    placeholder="Find your article..." 
    :showTips="true"
/>
```

### Header Integration Example
```blade
<header class="bg-white dark:bg-gray-800 shadow">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <x-public.search-box :showTips="true" />
    </div>
</header>
```

---

## Frontend Pagination Component

**Location:** `resources/views/components/public/frontend-pagination.blade.php`

### Purpose
Displays pagination controls for browsing through paginated content with previous/next navigation and page numbers.

### Properties
- `$items` (LengthAwarePaginator) - Paginated items collection
- `$showInfo` (Boolean, Optional) - Show pagination info (default: false)

### Features
- ✅ Previous/Next buttons
- ✅ Page number links
- ✅ Ellipsis for large page sets
- ✅ Current page highlight
- ✅ Disabled state for unavailable buttons
- ✅ Dark mode support
- ✅ Responsive design
- ✅ SEO-friendly links
- ✅ Optional pagination info text

### Usage Example
```blade
<x-public.frontend-pagination :items="$posts" />
```

### Usage with Info Display
```blade
<x-public.frontend-pagination 
    :items="$articles" 
    :showInfo="true"
/>
```

### Complete Page Example
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($posts as $post)
        <x-public.post-card :$post />
    @endforeach
</div>

<x-public.frontend-pagination :items="$posts" :showInfo="true" />
```

---

## Complete Page Example

Here's a complete example showing all components used together:

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    {{-- Search Section --}}
    <section class="py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-4xl font-bold text-center mb-6">Explore Our Articles</h1>
            <x-public.search-box :showTips="true" />
        </div>
    </section>

    {{-- Featured Posts Section --}}
    <x-public.featured-section
        :posts="$featuredPosts"
        title="Featured Articles"
        description="Don't miss our best content"
        viewAllRoute="{{ route('public.articles.index') }}"
    />

    {{-- All Posts with Pagination --}}
    <section class="py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
                <x-public.post-card :$post />
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No posts found.</p>
                </div>
            @endforelse
        </div>

        <x-public.frontend-pagination :items="$posts" :showInfo="true" />
    </section>

    {{-- Categories Section --}}
    <section class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8">Browse Categories</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <x-public.category-card :$category />
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
```

---

## Styling and Customization

All components use Tailwind CSS v4 and include dark mode support through the `dark:` prefix.

### Common Tailwind Classes Used
- `group` - For hover effects
- `group-hover:*` - For nested hover effects
- `line-clamp-*` - For text truncation
- `transition-*` - For smooth animations
- `dark:` - For dark mode variants

### Customizing Colors
To customize component colors, modify the Tailwind classes in the component files. For example, change `blue-600` to `indigo-600` for a different primary color.

### Responsive Breakpoints
- `sm:` - 640px
- `md:` - 768px  
- `lg:` - 1024px
- `xl:` - 1280px

---

## Translation and Multi-Language Support

All components support multi-language content through the application's locale detection system.

### Post Card Language Support
The post card component automatically uses the current locale to display:
- `title_id` / `title_en`
- `excerpt_id` / `excerpt_en`

### Implementation
```blade
{{ app()->getLocale() === 'id' ? $post->title_id : $post->title_en }}
```

---

## Performance Considerations

1. **Image Optimization**
   - Use optimized images for featured images
   - Consider lazy loading for images in lists

2. **Database Queries**
   - Load related data (categories, users) using eager loading
   - Example: `Article::with(['category', 'user'])->paginate()`

3. **Caching**
   - Cache featured posts if they don't change frequently
   - Cache category lists

---

## Accessibility

All components include accessibility features:
- Semantic HTML (`<article>`, `<nav>`)
- ARIA labels on buttons
- Proper heading hierarchy
- Color contrast for dark mode
- Keyboard navigation for pagination

---

## Testing Components

### Example Test for Post Card
```php
public function test_post_card_renders_with_post_data()
{
    $post = Article::factory()->create();
    
    $view = view('components.public.post-card', ['post' => $post]);
    
    $view->assertSee($post->title_id)
        ->assertSee($post->user->name)
        ->assertSee($post->created_at->format('M d, Y'));
}
```

---

## Summary

These 5 frontend components provide a consistent, reusable, and maintainable foundation for the public-facing pages of the CMS application. They support:

- ✅ Multi-language content
- ✅ Dark mode
- ✅ Responsive design
- ✅ SEO-friendly markup
- ✅ Accessibility
- ✅ Code reusability
- ✅ Easy customization

All components follow Laravel Blade best practices and Tailwind CSS conventions.
