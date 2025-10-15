<?php

namespace App\Http\Controllers\Api;

use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Page\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PublicController extends BaseController
{
    /**
     * Get public articles
     */
    public function articles(Request $request): JsonResponse
    {
        $query = Article::with(['category', 'user'])
            ->where('status', 'published');

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title_id', 'LIKE', "%{$request->search}%")
                  ->orWhere('title_en', 'LIKE', "%{$request->search}%")
                  ->orWhere('content_id', 'LIKE', "%{$request->search}%")
                  ->orWhere('content_en', 'LIKE', "%{$request->search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by featured
        if ($request->has('featured')) {
            $query->where('featured', $request->boolean('featured'));
        }

        // Sort
        $sortBy = $request->get('sort_by', 'published_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $articles = $query->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($articles, 'Public articles retrieved successfully');
    }

    /**
     * Get specific public article
     */
    public function article(Article $article): JsonResponse
    {
        if ($article->status !== 'published') {
            return $this->notFoundResponse('Article not found');
        }

        // Increment views
        $article->increment('views');

        return $this->successResponse([
            'article' => $article->load(['category', 'user'])
        ], 'Article retrieved successfully');
    }

    /**
     * Get public categories
     */
    public function categories(Request $request): JsonResponse
    {
        $query = Category::where('is_active', true);

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name_id', 'LIKE', "%{$request->search}%")
                  ->orWhere('name_en', 'LIKE', "%{$request->search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $categories = $query->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($categories, 'Public categories retrieved successfully');
    }

    /**
     * Get specific public category
     */
    public function category(Category $category): JsonResponse
    {
        if (!$category->is_active) {
            return $this->notFoundResponse('Category not found');
        }

        return $this->successResponse([
            'category' => $category
        ], 'Category retrieved successfully');
    }

    /**
     * Get public pages
     */
    public function pages(Request $request): JsonResponse
    {
        $query = Page::where('status', 'published');

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title_id', 'LIKE', "%{$request->search}%")
                  ->orWhere('title_en', 'LIKE', "%{$request->search}%")
                  ->orWhere('content_id', 'LIKE', "%{$request->search}%")
                  ->orWhere('content_en', 'LIKE', "%{$request->search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $pages = $query->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($pages, 'Public pages retrieved successfully');
    }

    /**
     * Get specific public page
     */
    public function page(Page $page): JsonResponse
    {
        if ($page->status !== 'published') {
            return $this->notFoundResponse('Page not found');
        }

        // Increment views
        $page->increment('views');

        return $this->successResponse([
            'page' => $page
        ], 'Page retrieved successfully');
    }

    /**
     * Search across all content
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->get('q', '');
        $type = $request->get('type', 'all'); // all, articles, pages, categories

        if (empty($searchTerm)) {
            return $this->errorResponse('Search term is required', 400);
        }

        $results = [];

        if ($type === 'all' || $type === 'articles') {
            $articles = Article::where('status', 'published')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('title_id', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('title_en', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('content_id', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('content_en', 'LIKE', "%{$searchTerm}%");
                })
                ->with(['category', 'user'])
                ->limit(10)
                ->get();

            $results['articles'] = $articles;
        }

        if ($type === 'all' || $type === 'pages') {
            $pages = Page::where('status', 'published')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('title_id', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('title_en', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('content_id', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('content_en', 'LIKE', "%{$searchTerm}%");
                })
                ->limit(10)
                ->get();

            $results['pages'] = $pages;
        }

        if ($type === 'all' || $type === 'categories') {
            $categories = Category::where('is_active', true)
                ->where(function ($q) use ($searchTerm) {
                    $q->where('name_id', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('name_en', 'LIKE', "%{$searchTerm}%");
                })
                ->limit(10)
                ->get();

            $results['categories'] = $categories;
        }

        return $this->successResponse([
            'search_term' => $searchTerm,
            'type' => $type,
            'results' => $results
        ], 'Search completed successfully');
    }
}
