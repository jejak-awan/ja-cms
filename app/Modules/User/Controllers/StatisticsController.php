<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserActivityLog;
use App\Modules\User\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $stats = $this->getUserStatistics();
        $charts = $this->getChartData();
        $recentActivity = $this->getRecentActivity();
        $topUsers = $this->getTopUsers();

        return view('admin.users.statistics.index', compact(
            'stats', 'charts', 'recentActivity', 'topUsers'
        ));
    }

    public function getChartData()
    {
        // User Growth Chart (Last 12 months)
        $userGrowth = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $userGrowth[] = [
                'month' => $date->format('M Y'),
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }

        // Role Distribution
        $roleDistribution = Role::withCount('users')->get()->map(function($role) {
            return [
                'role' => $role->display_name,
                'count' => $role->users_count,
                'percentage' => 0 // Will be calculated in frontend
            ];
        });

        // Status Distribution
        $statusDistribution = [
            ['status' => 'Active', 'count' => User::where('status', 'active')->count()],
            ['status' => 'Inactive', 'count' => User::where('status', 'inactive')->count()],
            ['status' => 'Suspended', 'count' => User::where('status', 'suspended')->count()],
        ];

        // Login Activity (Last 30 days)
        $loginActivity = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $loginActivity[] = [
                'date' => $date->format('M d'),
                'logins' => UserActivityLog::where('action', 'login')
                    ->whereDate('created_at', $date)
                    ->count()
            ];
        }

        // Geographic Distribution
        $geographicData = User::whereNotNull('location')
            ->select('location', DB::raw('count(*) as count'))
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return [
            'userGrowth' => $userGrowth,
            'roleDistribution' => $roleDistribution,
            'statusDistribution' => $statusDistribution,
            'loginActivity' => $loginActivity,
            'geographicData' => $geographicData
        ];
    }

    public function getUserStatistics()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $usersWithProfile = User::where(function($q) {
            $q->whereNotNull('phone')
              ->orWhereNotNull('location')
              ->orWhereNotNull('website')
              ->orWhereNotNull('bio');
        })->count();

        // Growth rate calculation
        $lastMonth = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
        $growthRate = $lastMonth > 0 ? (($newUsersThisMonth - $lastMonth) / $lastMonth) * 100 : 0;

        // Average session duration (simplified)
        $avgSessionDuration = UserActivityLog::where('action', 'login')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->count() * 45; // Simplified calculation

        return [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'newUsersThisMonth' => $newUsersThisMonth,
            'verifiedUsers' => $verifiedUsers,
            'usersWithProfile' => $usersWithProfile,
            'growthRate' => round($growthRate, 1),
            'avgSessionDuration' => $avgSessionDuration,
            'inactiveUsers' => User::where('status', 'inactive')->count(),
            'suspendedUsers' => User::where('status', 'suspended')->count(),
        ];
    }

    public function getRecentActivity()
    {
        return UserActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($log) {
                return [
                    'id' => $log->id,
                    'user' => $log->user ? $log->user->name : 'System',
                    'action' => $log->action,
                    'description' => $log->description,
                    'created_at' => $log->created_at->format('M d, Y H:i'),
                    'status' => $log->status,
                ];
            });
    }

    public function getTopUsers()
    {
        // Users with most activity
        return User::withCount('activityLogs')
            ->orderBy('activity_logs_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'activity_count' => $user->activity_logs_count,
                    'last_login' => $user->last_login_at ? $user->last_login_at->format('M d, Y') : 'Never',
                ];
            });
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $type = $request->get('type', 'overview');

        switch ($type) {
            case 'overview':
                return $this->exportOverview($format);
            case 'growth':
                return $this->exportGrowth($format);
            case 'activity':
                return $this->exportActivity($format);
            case 'geographic':
                return $this->exportGeographic($format);
            default:
                return response()->json(['error' => 'Invalid export type'], 400);
        }
    }

    private function exportOverview($format)
    {
        $stats = $this->getUserStatistics();
        $data = [
            ['Metric', 'Value'],
            ['Total Users', $stats['totalUsers']],
            ['Active Users', $stats['activeUsers']],
            ['New Users This Month', $stats['newUsersThisMonth']],
            ['Verified Users', $stats['verifiedUsers']],
            ['Users with Profile', $stats['usersWithProfile']],
            ['Growth Rate (%)', $stats['growthRate']],
            ['Inactive Users', $stats['inactiveUsers']],
            ['Suspended Users', $stats['suspendedUsers']],
        ];

        return $this->exportData($data, 'user_statistics_overview', $format);
    }

    private function exportGrowth($format)
    {
        $growthData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $growthData[] = [
                'Month' => $date->format('M Y'),
                'New Users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }

        array_unshift($growthData, ['Month', 'New Users']);
        return $this->exportData($growthData, 'user_growth_trend', $format);
    }

    private function exportActivity($format)
    {
        $activityData = UserActivityLog::with('user')
            ->latest()
            ->limit(1000)
            ->get()
            ->map(function($log) {
                return [
                    'Date' => $log->created_at->format('Y-m-d H:i:s'),
                    'User' => $log->user ? $log->user->name : 'System',
                    'Action' => $log->action,
                    'Description' => $log->description,
                    'Status' => $log->status,
                    'IP Address' => $log->ip_address,
                ];
            })->toArray();

        array_unshift($activityData, ['Date', 'User', 'Action', 'Description', 'Status', 'IP Address']);
        return $this->exportData($activityData, 'user_activity_logs', $format);
    }

    private function exportGeographic($format)
    {
        $geoData = User::whereNotNull('location')
            ->select('location', DB::raw('count(*) as count'))
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'Location' => $item->location,
                    'User Count' => $item->count
                ];
            })->toArray();

        array_unshift($geoData, ['Location', 'User Count']);
        return $this->exportData($geoData, 'user_geographic_distribution', $format);
    }

    private function exportData($data, $filename, $format)
    {
        $filename = $filename . '_' . date('Y-m-d_H-i-s') . '.' . $format;

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } else {
            return response()->json($data, 200, [
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }
    }

    public function getApiData(Request $request)
    {
        $type = $request->get('type', 'overview');

        switch ($type) {
            case 'overview':
                return response()->json($this->getUserStatistics());
            case 'charts':
                return response()->json($this->getChartData());
            case 'activity':
                return response()->json($this->getRecentActivity());
            case 'top-users':
                return response()->json($this->getTopUsers());
            default:
                return response()->json(['error' => 'Invalid data type'], 400);
        }
    }
}
