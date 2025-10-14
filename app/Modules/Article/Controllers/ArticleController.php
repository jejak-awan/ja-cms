<?php

namespace App\Modules\Article\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Tag\Models\Tag;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles with filters
     */
    public function index(Request $request)
    {
        $query = Article::with(['category', 'user', 'tags']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Author filter
        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Cache articles list for 2 minutes (only for default filter, page 1, no search/filter)
        if (!$request->filled('search') && !$request->filled('category') && !$request->filled('status') && !$request->filled('author') && !$request->filled('date_from') && !$request->filled('date_to') && $request->get('page', 1) == 1) {
            $articles = cache()->remember('admin_articles_index_page1', 120, function() use ($query) {
                return $query->paginate(15)->withQueryString();
            });
        } else {
            $articles = $query->paginate(15)->withQueryString();
        }
        
        // Get data for filters
        $categories = Category::active()->get();
        $authors = User::active()->get();
        $statuses = ['draft', 'published', 'scheduled'];

        if ($request->wantsJson()) {
            return response()->json($articles);
        }

        return view('admin.articles.index', compact('articles', 'categories', 'authors', 'statuses'));
    }

    /**
     * Show the form for creating a new article
     */
    public function create()
    {
        $categories = Category::active()->get();
        $tags = Tag::all();
        return view('admin.articles.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:articles,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('articles', 'public');
        }

        // Set user_id
        $validated['user_id'] = auth()->id();

        // Handle published_at
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Create article
        $article = Article::create($validated);

        // Attach tags
        if ($request->filled('tags')) {
            $article->tags()->attach($request->tags);
        }

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified article
     */
    public function show($id)
    {
        $article = Article::with(['category', 'user', 'tags'])->findOrFail($id);
        
        if (request()->wantsJson()) {
            return response()->json($article);
        }

        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit($id)
    {
        $article = Article::with('tags')->findOrFail($id);
        $categories = Category::active()->get();
        $tags = Tag::all();
        
        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:articles,slug,' . $id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            
            $validated['featured_image'] = $request->file('featured_image')
                ->store('articles', 'public');
        }

        // Handle published_at
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Update article
        $article->update($validated);

        // Sync tags
        if ($request->has('tags')) {
            $article->tags()->sync($request->tags ?? []);
        }

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified article
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        
        // Delete featured image
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
        
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article deleted successfully!');
    }

    /**
     * Bulk actions for articles
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,publish,draft',
            'articles' => 'required|array',
            'articles.*' => 'exists:articles,id',
        ]);

        $articles = Article::whereIn('id', $request->articles)->get();

        switch ($request->action) {
            case 'delete':
                foreach ($articles as $article) {
                    if ($article->featured_image) {
                        Storage::disk('public')->delete($article->featured_image);
                    }
                    $article->delete();
                }
                $message = count($articles) . ' articles deleted successfully!';
                break;

            case 'publish':
                $articles->each(function($article) {
                    $article->update([
                        'status' => 'published',
                        'published_at' => $article->published_at ?? now(),
                    ]);
                });
                $message = count($articles) . ' articles published successfully!';
                break;

            case 'draft':
                $articles->each(function($article) {
                    $article->update(['status' => 'draft']);
                });
                $message = count($articles) . ' articles moved to draft!';
                break;
        }

        return redirect()
            ->route('admin.articles.index')
            ->with('success', $message);
    }
}
