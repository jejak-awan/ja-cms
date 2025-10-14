<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Page\Models\Page;
use App\Modules\Media\Models\Media;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with statistics
     */
    public function dashboard()
    {
        $stats = [
            'articles' => [
                'total' => Article::count(),
                'published' => Article::where('status', 'published')->count(),
                'draft' => Article::where('status', 'draft')->count(),
                'recent' => Article::with('user', 'category')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
            ],
            'pages' => [
                'total' => Page::count(),
                'published' => Page::where('status', 'published')->count(),
                'draft' => Page::where('status', 'draft')->count(),
            ],
            'categories' => [
                'total' => Category::count(),
                'active' => Category::where('is_active', true)->count(),
            ],
            'media' => [
                'total' => Media::count(),
                'images' => Media::whereIn('mime_type', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'])->count(),
                'documents' => Media::whereIn('mime_type', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'text/plain'])->count(),
                'total_size' => Media::sum('size'),
            ],
            'users' => [
                'total' => User::count(),
                'active' => User::where('status', 'active')->count(),
                'admins' => User::where('role', 'admin')->count(),
                'recent' => User::orderBy('created_at', 'desc')->limit(5)->get(),
            ],
        ];

        // Chart data for last 6 months
        $chartData = $this->getChartData();
        
        // Activity feed data
        $activities = $this->getActivityFeed();

        return view('admin.dashboard', compact('stats', 'chartData', 'activities'));
    }

    /**
     * Get chart data for dashboard analytics
     */
    private function getChartData()
    {
        $months = [];
        $articlesData = [];
        $usersData = [];
        
        // Generate last 6 months labels
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            // Articles created/published in this month (fallback to created_at if published_at null)
            $articlesCount = Article::where(function($q) use ($date) {
                    $q->whereYear('published_at', $date->year)
                        ->whereMonth('published_at', $date->month)
                        ->where('status', 'published');
                })
                ->orWhere(function($q) use ($date) {
                    $q->whereNull('published_at')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month);
                })
                ->count();
            $articlesData[] = $articlesCount;
            
            // Users registered in this month
            $usersCount = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $usersData[] = $usersCount;
        }

        return [
            'labels' => $months,
            'articles' => $articlesData,
            'users' => $usersData,
            'content' => [
                'articles' => Article::count(),
                'pages' => Page::count(),
                'categories' => Category::count(),
                'media' => Media::count(),
            ]
        ];
    }

    /**
     * Get activity feed data
     */
    private function getActivityFeed($page = 1, $perPage = 10, $type = null)
    {
        $activities = collect();

        // Recent articles
        $articleQuery = Article::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($type === 'article' ? $perPage : 5);
        $recentArticles = $articleQuery->get()
            ->map(function ($article) {
                return [
                    'type' => 'article',
                    'icon' => 'document-text',
                    'color' => 'blue',
                    'title' => 'New article published',
                    'description' => $article->title,
                    'user' => $article->user->name,
                    'time' => $article->created_at,
                    'url' => "/admin/articles/{$article->id}/edit"
                ];
            });

        // Recent users
        $userQuery = User::orderBy('created_at', 'desc')
            ->limit($type === 'user' ? $perPage : 3);
        $recentUsers = $userQuery->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'icon' => 'user-plus',
                    'color' => 'green',
                    'title' => 'New user registered',
                    'description' => $user->name,
                    'user' => 'System',
                    'time' => $user->created_at,
                    'url' => "/admin/users/{$user->id}/edit"
                ];
            });

        // Recent media uploads
        $mediaQuery = Media::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($type === 'media' ? $perPage : 3);
        $recentMedia = $mediaQuery->get()
            ->map(function ($media) {
                return [
                    'type' => 'media',
                    'icon' => 'photograph',
                    'color' => 'yellow',
                    'title' => 'Media file uploaded',
                    'description' => $media->filename,
                    'user' => $media->user->name ?? 'System',
                    'time' => $media->created_at,
                    'url' => '/admin/media'
                ];
            });

        // Filter by type if specified
        if ($type) {
            switch ($type) {
                case 'article':
                    $activities = $activities->merge($recentArticles);
                    break;
                case 'user':
                    $activities = $activities->merge($recentUsers);
                    break;
                case 'media':
                    $activities = $activities->merge($recentMedia);
                    break;
            }
        } else {
            $activities = $activities->merge($recentArticles)
                ->merge($recentUsers)
                ->merge($recentMedia);
        }

        $sorted = $activities->sortByDesc('time');
        // Simple pagination
        $offset = ($page - 1) * $perPage;
        return $sorted->slice($offset, $perPage)->values();
    }

    /**
     * Get activity feed API endpoint
     */
    public function activityFeed(Request $request)
    {
        $type = $request->get('type');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $activities = $this->getActivityFeed($page, $perPage, $type);

        return response()->json([
            'success' => true,
            'data' => $activities,
            'page' => (int) $page,
            'per_page' => (int) $perPage,
            'type' => $type,
        ]);
    }
}
