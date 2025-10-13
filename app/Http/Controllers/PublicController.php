<?php

namespace App\Http\Controllers;

use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Page\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Show homepage
     */
    public function index(): View
    {
        $featuredArticles = Article::where('status', 'published')
            ->where('featured', true)
            ->latest('published_at')
            ->take(3)
            ->get();
            
        $latestArticles = Article::where('status', 'published')
            ->latest('published_at')
            ->take(6)
            ->get();
            
        $categories = Category::withCount('articles')
            ->orderBy('name')
            ->take(6)
            ->get();
            
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
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%");
            });
        }
        
        $articles = $query->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(12);
            
        $categories = Category::withCount('articles')
            ->orderBy('name')
            ->get();
            
        return view('public.pages.articles', compact('articles', 'categories'));
    }
    
    /**
     * Show single article
     */
    public function article(string $slug): View
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->with(['category', 'author'])
            ->firstOrFail();
            
        // Increment view count
        $article->increment('views');
        
        // Get related articles (same category)
        $relatedArticles = Article::where('status', 'published')
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();
            
        return view('public.pages.article-detail', compact('article', 'relatedArticles'));
    }
    
    /**
     * Show categories listing
     */
    public function categories(): View
    {
        $categories = Category::withCount('articles')
            ->orderBy('name')
            ->paginate(12);
            
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
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
            
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
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(10, ['*'], 'articles_page');
            
        // Search pages
        $pages = Page::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
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
