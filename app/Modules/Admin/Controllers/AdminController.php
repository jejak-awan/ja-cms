<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\Page\Models\Page;
use App\Modules\Media\Models\Media;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;

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

        return view('admin.dashboard', compact('stats'));
    }
}
