<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Page\Models\Page;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'articles' => Article::count(),
            'published_articles' => Article::where('status', 'published')->count(),
            'draft_articles' => Article::where('status', 'draft')->count(),
            'categories' => Category::count(),
            'pages' => Page::count(),
            'users' => User::count(),
        ];

        // Get chart data (last 7 days)
        $chartData = [
            'labels' => [],
            'articles' => [],
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData['labels'][] = $date->format('M d');
            $chartData['articles'][] = Article::whereDate('created_at', $date)->count();
        }

        // Get recent activities
        $activities = $this->getRecentActivities();

        return view('admin.dashboard', compact('stats', 'chartData', 'activities'));
    }

    /**
     * Get activity feed (API endpoint)
     */
    public function activityFeed(Request $request)
    {
        $type = $request->get('type');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $activities = $this->getRecentActivities($type, $page, $perPage);

        return response()->json([
            'success' => true,
            'data' => $activities,
            'page' => $page,
            'per_page' => $perPage,
            'type' => $type,
        ]);
    }

    /**
     * Get recent activities helper
     */
    protected function getRecentActivities($type = null, $page = 1, $perPage = 10)
    {
        $activities = collect();

        if (!$type || $type === 'article') {
            $articles = Article::with('user')
                ->latest()
                ->limit($perPage)
                ->get()
                ->map(function ($article) {
                    return [
                        'type' => 'article',
                        'title' => $article->title,
                        'user' => $article->user->name ?? 'Unknown',
                        'action' => 'created',
                        'created_at' => $article->created_at,
                    ];
                });
            $activities = $activities->merge($articles);
        }

        if (!$type || $type === 'page') {
            $pages = Page::with('user')
                ->latest()
                ->limit($perPage)
                ->get()
                ->map(function ($page) {
                    return [
                        'type' => 'page',
                        'title' => $page->title,
                        'user' => $page->user->name ?? 'Unknown',
                        'action' => 'created',
                        'created_at' => $page->created_at,
                    ];
                });
            $activities = $activities->merge($pages);
        }

        if (!$type || $type === 'user') {
            $users = User::latest()
                ->limit($perPage)
                ->get()
                ->map(function ($user) {
                    return [
                        'type' => 'user',
                        'title' => $user->name,
                        'user' => 'System',
                        'action' => 'registered',
                        'created_at' => $user->created_at,
                    ];
                });
            $activities = $activities->merge($users);
        }

        return $activities->sortByDesc('created_at')->take($perPage)->values();
    }
}
