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
                'published' => Article::published()->count(),
                'draft' => Article::draft()->count(),
                'recent' => Article::with('user', 'category')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
            ],
            'pages' => [
                'total' => Page::count(),
                'published' => Page::published()->count(),
                'draft' => Page::draft()->count(),
            ],
            'categories' => [
                'total' => Category::count(),
                'active' => Category::active()->count(),
            ],
            'media' => [
                'total' => Media::count(),
                'images' => Media::images()->count(),
                'documents' => Media::documents()->count(),
                'total_size' => Media::getTotalSize(),
            ],
            'users' => [
                'total' => User::count(),
                'active' => User::active()->count(),
                'admins' => User::whereHas('roles', function($q) {
                    $q->where('name', 'admin');
                })->count(),
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
            
            // Articles published in this month
            $articlesCount = Article::whereYear('published_at', $date->year)
                ->whereMonth('published_at', $date->month)
                ->where('status', 'published')
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
    private function getActivityFeed()
    {
        $activities = collect();

        // Recent articles
        $recentArticles = Article::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
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
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
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
        $recentMedia = Media::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
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

        return $activities
            ->merge($recentArticles)
            ->merge($recentUsers)
            ->merge($recentMedia)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }
}
