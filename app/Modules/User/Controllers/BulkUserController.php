<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BulkUserController extends Controller
{
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:activate,deactivate,suspend,delete,change_role,export',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
            'role' => 'required_if:action,change_role|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data',
                'errors' => $validator->errors()
            ], 422);
        }

        $action = $request->action;
        $userIds = $request->user_ids;
        $users = User::whereIn('id', $userIds)->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No users found'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $results = [];
            $successCount = 0;
            $errorCount = 0;

            foreach ($users as $user) {
                try {
                    switch ($action) {
                        case 'activate':
                            $user->activate();
                            $results[] = "User {$user->name} activated successfully";
                            $successCount++;
                            break;

                        case 'deactivate':
                            $user->deactivate();
                            $results[] = "User {$user->name} deactivated successfully";
                            $successCount++;
                            break;

                        case 'suspend':
                            $user->suspend();
                            $results[] = "User {$user->name} suspended successfully";
                            $successCount++;
                            break;

                        case 'delete':
                            // Prevent deleting admin users
                            if ($user->is_admin) {
                                $results[] = "Cannot delete admin user: {$user->name}";
                                $errorCount++;
                                break;
                            }
                            $user->delete();
                            $results[] = "User {$user->name} deleted successfully";
                            $successCount++;
                            break;

                        case 'change_role':
                            $user->update(['role' => $request->role]);
                            $results[] = "User {$user->name} role changed to {$request->role}";
                            $successCount++;
                            break;

                        case 'export':
                            // This will be handled separately
                            break;
                    }
                } catch (\Exception $e) {
                    $results[] = "Error processing user {$user->name}: " . $e->getMessage();
                    $errorCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Bulk action completed. {$successCount} users processed successfully, {$errorCount} errors.",
                'results' => $results,
                'success_count' => $successCount,
                'error_count' => $errorCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk action failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
            'format' => 'required|string|in:csv,json,xlsx'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data',
                'errors' => $validator->errors()
            ], 422);
        }

        $users = User::whereIn('id', $request->user_ids)->get();
        $format = $request->format;

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No users found to export'
            ], 404);
        }

        try {
            $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.' . $format;
            
            if ($format === 'csv') {
                return $this->exportToCsv($users, $filename);
            } elseif ($format === 'json') {
                return $this->exportToJson($users, $filename);
            } elseif ($format === 'xlsx') {
                return $this->exportToXlsx($users, $filename);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function exportToCsv($users, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Role', 'Status', 'Phone', 'Location', 
                'Website', 'Bio', 'Created At', 'Last Login'
            ]);

            // CSV Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->status,
                    $user->phone ?? '',
                    $user->location ?? '',
                    $user->website ?? '',
                    $user->bio ?? '',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToJson($users, $filename)
    {
        $data = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'phone' => $user->phone,
                'location' => $user->location,
                'website' => $user->website,
                'bio' => $user->bio,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'last_login_at' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : null
            ];
        });

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportToXlsx($users, $filename)
    {
        // For XLSX export, we'll use a simple approach
        // In a real application, you might want to use PhpSpreadsheet
        $csvData = [];
        
        // Headers
        $csvData[] = [
            'ID', 'Name', 'Email', 'Role', 'Status', 'Phone', 'Location', 
            'Website', 'Bio', 'Created At', 'Last Login'
        ];

        // Data
        foreach ($users as $user) {
            $csvData[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->status,
                $user->phone ?? '',
                $user->location ?? '',
                $user->website ?? '',
                $user->bio ?? '',
                $user->created_at->format('Y-m-d H:i:s'),
                $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never'
            ];
        }

        // Convert to XLSX format (simplified)
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->json($csvData, 200, $headers);
    }

    public function getBulkActionStats(Request $request)
    {
        $userIds = $request->user_ids ?? [];
        
        if (empty($userIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No users selected'
            ], 400);
        }

        $users = User::whereIn('id', $userIds)->get();
        
        $stats = [
            'total_selected' => $users->count(),
            'by_status' => $users->groupBy('status')->map->count(),
            'by_role' => $users->groupBy('role')->map->count(),
            'admin_users' => $users->where('role', 'admin')->count(),
            'verified_users' => $users->whereNotNull('email_verified_at')->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
