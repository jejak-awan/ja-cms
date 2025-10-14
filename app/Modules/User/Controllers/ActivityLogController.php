<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = UserActivityLog::with('user')->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('action', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                               ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $activityLogs = $query->paginate(50);
        $users = User::select('id', 'name', 'email')->get();
        
        $stats = [
            'total' => UserActivityLog::count(),
            'today' => UserActivityLog::today()->count(),
            'this_week' => UserActivityLog::thisWeek()->count(),
            'this_month' => UserActivityLog::thisMonth()->count(),
            'failed_logins' => UserActivityLog::byAction('failed_login')->count(),
            'successful_logins' => UserActivityLog::byAction('login')->count(),
        ];

        $actions = UserActivityLog::distinct()->pluck('action')->sort();
        $statuses = UserActivityLog::distinct()->pluck('status')->sort();

        return view('admin.users.activity-logs.index', compact(
            'activityLogs', 'users', 'stats', 'actions', 'statuses'
        ));
    }

    public function show(UserActivityLog $activityLog)
    {
        return view('admin.users.activity-logs.show', compact('activityLog'));
    }

    public function userActivity(User $user, Request $request)
    {
        $query = $user->activityLogs()->latest();

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->paginate(30);
        
        $stats = [
            'total' => $user->activityLogs()->count(),
            'today' => $user->activityLogs()->today()->count(),
            'this_week' => $user->activityLogs()->thisWeek()->count(),
            'this_month' => $user->activityLogs()->thisMonth()->count(),
            'last_login' => $user->activityLogs()->byAction('login')->latest()->first(),
            'last_activity' => $user->activityLogs()->latest()->first(),
        ];

        $actions = $user->activityLogs()->distinct()->pluck('action')->sort();
        $statuses = $user->activityLogs()->distinct()->pluck('status')->sort();

        return view('admin.users.activity-logs.user-activity', compact(
            'user', 'activityLogs', 'stats', 'actions', 'statuses'
        ));
    }

    public function statistics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        
        $stats = [
            'total_activities' => UserActivityLog::recent($period)->count(),
            'unique_users' => UserActivityLog::recent($period)->distinct('user_id')->count(),
            'login_attempts' => UserActivityLog::recent($period)->byAction('login')->count(),
            'failed_logins' => UserActivityLog::recent($period)->byAction('failed_login')->count(),
            'profile_updates' => UserActivityLog::recent($period)->byAction('profile_update')->count(),
            'password_changes' => UserActivityLog::recent($period)->byAction('password_change')->count(),
        ];

        // Daily activity for the last 30 days
        $dailyActivity = UserActivityLog::recent($period)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        // Activity by action
        $activityByAction = UserActivityLog::recent($period)
            ->select('action', DB::raw('COUNT(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->get();

        // Activity by status
        $activityByStatus = UserActivityLog::recent($period)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();

        // Top active users
        $topActiveUsers = UserActivityLog::recent($period)
            ->select('user_id', DB::raw('COUNT(*) as activity_count'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->with('user:id,name,email')
            ->orderBy('activity_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.users.activity-logs.statistics', compact(
            'stats', 'dailyActivity', 'activityByAction', 'activityByStatus', 'topActiveUsers', 'period'
        ));
    }

    public function export(Request $request)
    {
        $query = UserActivityLog::with('user')->latest();

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->limit(1000)->get(); // Limit to prevent memory issues
        $format = $request->get('format', 'csv');

        $filename = 'activity_logs_export_' . date('Y-m-d_H-i-s') . '.' . $format;

        if ($format === 'csv') {
            return $this->exportToCsv($activityLogs, $filename);
        } elseif ($format === 'json') {
            return $this->exportToJson($activityLogs, $filename);
        }

        return response()->json(['error' => 'Unsupported format'], 400);
    }

    private function exportToCsv($activityLogs, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($activityLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID', 'User', 'Action', 'Description', 'IP Address', 'Status', 'Created At'
            ]);

            // CSV Data
            foreach ($activityLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'N/A',
                    $log->action,
                    $log->description,
                    $log->ip_address,
                    $log->status,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToJson($activityLogs, $filename)
    {
        $data = $activityLogs->map(function($log) {
            return [
                'id' => $log->id,
                'user' => $log->user ? [
                    'name' => $log->user->name,
                    'email' => $log->user->email
                ] : null,
                'action' => $log->action,
                'description' => $log->description,
                'ip_address' => $log->ip_address,
                'status' => $log->status,
                'metadata' => $log->metadata,
                'created_at' => $log->created_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
