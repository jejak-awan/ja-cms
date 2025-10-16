<?php

namespace App\Http\Controllers;

use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Page\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Support\CacheHelper;

class PublicController extends Controller
{
    /**
     * Show homepage
     */
    public function index(): View
    {
        // Cache featured articles for 15 minutes
        $featuredArticles = CacheHelper::remember('public_featured_articles', 'article', 900, function() {
            return Article::where('status', 'published')
                ->where('featured', true)
                ->latest('published_at')
                ->take(3)
                ->get();
        });
            
        // Cache latest articles for 10 minutes
        $latestArticles = CacheHelper::remember('public_latest_articles', 'article', 600, function() {
            return Article::where('status', 'published')
                ->latest('published_at')
                ->take(6)
                ->get();
        });
            
        // Cache categories for 30 minutes
        $categories = CacheHelper::remember('public_categories_homepage', 'category', 1800, function() {
            return Category::withCount('articles')
                ->orderBy('name')
                ->take(6)
                ->get();
        });
            
        return view('public.pages.home', compact(
            'featuredArticles',
            'latestArticles',
            'categories'
        ));
    }
    
    /**
     * Show articles listing
     */
    public function articles(Request $request): View
    {
        $query = Article::where('status', 'published');
        
        // Filter by category if provided
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Search functionality
        if ($request->has('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title_id', 'like', "%{$searchTerm}%")
                  ->orWhere('content_id', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt_id', 'like', "%{$searchTerm}%")
                  ->orWhere('title_en', 'like', "%{$searchTerm}%")
                  ->orWhere('content_en', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt_en', 'like', "%{$searchTerm}%");
            });
        }
        
        $articles = $query->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(12);
            
        // Cache categories for 30 minutes
        $categories = CacheHelper::remember('public_categories_list', 'category', 1800, function() {
            return Category::withCount('articles')
                ->orderBy('name')
                ->get();
        });

        return view('public.pages.articles', compact('articles', 'categories'));
    }
    
    /**
     * Show single article
     */
    public function article(string $slug): View
    {
        // Cache article content for 30 minutes (excluding view count)
        $article = CacheHelper::remember("public_article_{$slug}", 'article', 1800, function() use ($slug) {
            return Article::where('slug', $slug)
                ->where('status', 'published')
                ->with(['category', 'author'])
                ->firstOrFail();
        });
            
        // Increment view count (not cached)
        Article::where('slug', $slug)->increment('views');
        
        // Cache related articles for 20 minutes
        $relatedArticles = CacheHelper::remember("public_related_articles_{$article->category_id}_{$article->id}", 'article', 1200, function() use ($article) {
            return Article::where('status', 'published')
                ->where('category_id', $article->category_id)
                ->where('id', '!=', $article->id)
                ->latest('published_at')
                ->take(3)
                ->get();
        });
            
        return view('public.pages.article-detail', compact('article', 'relatedArticles'));
    }
    
    /**
     * Show categories listing
     */
    public function categories(): View
    {
        // Cache categories for 30 minutes
        $categories = cache()->remember('public_categories_list', 1800, function() {
            return Category::withCount('articles')
                ->orderBy('name')
                ->get();
        });

        return view('public.pages.categories', compact('categories'));
    }
    
    /**
     * Show single category with its articles
     */
    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)
            ->withCount('articles')
            ->firstOrFail();
            
        $articles = Article::where('status', 'published')
            ->where('category_id', $category->id)
            ->with(['author'])
            ->latest('published_at')
            ->paginate(12);
            
        return view('public.pages.category-detail', compact('category', 'articles'));
    }
    
    /**
     * Show single page
     */
    public function page(string $slug): View
    {
        // Cache page content for 60 minutes
        $page = CacheHelper::remember("public_page_{$slug}", 'page', 3600, function() use ($slug) {
            return Page::where('slug', $slug)
                ->where('status', 'published')
                ->firstOrFail();
        });
            
        // Use custom template if specified
        $template = $page->template ?? 'default';
        $viewPath = "public.pages.page-{$template}";
        
        // Fallback to default page template if custom doesn't exist
        if (!view()->exists($viewPath)) {
            $viewPath = 'public.pages.page-default';
        }
        
        return view($viewPath, compact('page'));
    }
    
    /**
     * Show search results
     */
    public function search(Request $request): View
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return redirect()->route('home');
        }
        
        // Search articles
        $articles = Article::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title_id', 'like', "%{$query}%")
                  ->orWhere('content_id', 'like', "%{$query}%")
                  ->orWhere('excerpt_id', 'like', "%{$query}%")
                  ->orWhere('title_en', 'like', "%{$query}%")
                  ->orWhere('content_en', 'like', "%{$query}%")
                  ->orWhere('excerpt_en', 'like', "%{$query}%");
            })
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(10, ['*'], 'articles_page');
            
        // Search pages
        $pages = Page::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title_id', 'like', "%{$query}%")
                  ->orWhere('content_id', 'like', "%{$query}%")
                  ->orWhere('title_en', 'like', "%{$query}%")
                  ->orWhere('content_en', 'like', "%{$query}%");
            })
            ->latest('updated_at')
            ->paginate(10, ['*'], 'pages_page');
            
        return view('public.pages.search', compact('query', 'articles', 'pages'));
    }
    
    /**
     * API endpoint to increment article view count
     */
    public function incrementView(Request $request, int $id)
    {
        $article = Article::findOrFail($id);
        $article->increment('views');
        
        return response()->json(['success' => true, 'views' => $article->views]);
    }
}
