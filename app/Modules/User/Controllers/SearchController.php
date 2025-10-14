<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Advanced Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%')
                  ->orWhere('website', 'like', '%' . $searchTerm . '%')
                  ->orWhere('bio', 'like', '%' . $searchTerm . '%');
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date Range Filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Last Login Filter
        if ($request->filled('last_login_from')) {
            $query->whereDate('last_login_at', '>=', $request->last_login_from);
        }

        if ($request->filled('last_login_to')) {
            $query->whereDate('last_login_at', '<=', $request->last_login_to);
        }

        // Location Filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Has Profile Filter
        if ($request->filled('has_profile')) {
            if ($request->has_profile === 'yes') {
                $query->where(function($q) {
                    $q->whereNotNull('phone')
                      ->orWhereNotNull('location')
                      ->orWhereNotNull('website')
                      ->orWhereNotNull('bio');
                });
            } elseif ($request->has_profile === 'no') {
                $query->where(function($q) {
                    $q->whereNull('phone')
                      ->whereNull('location')
                      ->whereNull('website')
                      ->whereNull('bio');
                });
            }
        }

        // Email Verified Filter
        if ($request->filled('email_verified')) {
            if ($request->email_verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->email_verified === 'no') {
                $query->whereNull('email_verified_at');
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['name', 'email', 'role', 'status', 'created_at', 'last_login_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $users = $query->paginate(20)->withQueryString();

        // Get filter options
        $roles = Role::orderBy('display_name')->get();
        $statuses = ['active', 'inactive', 'suspended'];
        $locations = User::whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values();

        // Statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'with_profile' => User::where(function($q) {
                $q->whereNotNull('phone')
                  ->orWhereNotNull('location')
                  ->orWhereNotNull('website')
                  ->orWhereNotNull('bio');
            })->count(),
        ];

        // Search suggestions
        $suggestions = $this->getSearchSuggestions($request);

        return view('admin.users.search.index', compact(
            'users', 'roles', 'statuses', 'locations', 'stats', 'suggestions'
        ));
    }

    public function export(Request $request)
    {
        $query = User::query();

        // Apply same filters as search
        $this->applyFilters($query, $request);

        $users = $query->get();
        $format = $request->get('format', 'csv');
        $fields = $request->get('fields', ['id', 'name', 'email', 'role', 'status', 'created_at']);

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No users found to export'
            ], 404);
        }

        $filename = 'users_search_results_' . date('Y-m-d_H-i-s') . '.' . $format;

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($users, $fields, $filename);
            case 'json':
                return $this->exportToJson($users, $fields, $filename);
            default:
                return response()->json(['error' => 'Unsupported format'], 400);
        }
    }

    public function saveSearch(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'filters' => 'required|array',
        ]);

        // In a real application, you might want to save search queries to database
        // For now, we'll just return success
        return response()->json([
            'success' => true,
            'message' => 'Search saved successfully'
        ]);
    }

    public function getSavedSearches()
    {
        // In a real application, you might want to retrieve saved searches from database
        return response()->json([
            'searches' => [
                [
                    'id' => 1,
                    'name' => 'Active Users',
                    'filters' => ['status' => 'active'],
                    'created_at' => now()->subDays(1)->format('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,
                    'name' => 'Recent Signups',
                    'filters' => ['date_from' => now()->subWeek()->format('Y-m-d')],
                    'created_at' => now()->subDays(3)->format('Y-m-d H:i:s')
                ]
            ]
        ]);
    }

    private function applyFilters($query, $request)
    {
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%')
                  ->orWhere('website', 'like', '%' . $searchTerm . '%')
                  ->orWhere('bio', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
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

        if ($request->filled('last_login_from')) {
            $query->whereDate('last_login_at', '>=', $request->last_login_from);
        }

        if ($request->filled('last_login_to')) {
            $query->whereDate('last_login_at', '<=', $request->last_login_to);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('has_profile')) {
            if ($request->has_profile === 'yes') {
                $query->where(function($q) {
                    $q->whereNotNull('phone')
                      ->orWhereNotNull('location')
                      ->orWhereNotNull('website')
                      ->orWhereNotNull('bio');
                });
            } elseif ($request->has_profile === 'no') {
                $query->where(function($q) {
                    $q->whereNull('phone')
                      ->whereNull('location')
                      ->whereNull('website')
                      ->whereNull('bio');
                });
            }
        }

        if ($request->filled('email_verified')) {
            if ($request->email_verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->email_verified === 'no') {
                $query->whereNull('email_verified_at');
            }
        }
    }

    private function getSearchSuggestions($request)
    {
        $suggestions = [];

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            // Name suggestions
            $nameSuggestions = User::where('name', 'like', '%' . $searchTerm . '%')
                ->distinct()
                ->pluck('name')
                ->take(5);
            
            // Email suggestions
            $emailSuggestions = User::where('email', 'like', '%' . $searchTerm . '%')
                ->distinct()
                ->pluck('email')
                ->take(5);
            
            // Location suggestions
            $locationSuggestions = User::where('location', 'like', '%' . $searchTerm . '%')
                ->distinct()
                ->pluck('location')
                ->take(5);

            $suggestions = [
                'names' => $nameSuggestions,
                'emails' => $emailSuggestions,
                'locations' => $locationSuggestions,
            ];
        }

        return $suggestions;
    }

    private function exportToCsv($users, $fields, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users, $fields) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, $fields);

            // CSV Data
            foreach ($users as $user) {
                $row = [];
                foreach ($fields as $field) {
                    switch ($field) {
                        case 'id':
                            $row[] = $user->id;
                            break;
                        case 'name':
                            $row[] = $user->name;
                            break;
                        case 'email':
                            $row[] = $user->email;
                            break;
                        case 'role':
                            $row[] = $user->role;
                            break;
                        case 'status':
                            $row[] = $user->status;
                            break;
                        case 'phone':
                            $row[] = $user->phone ?? '';
                            break;
                        case 'location':
                            $row[] = $user->location ?? '';
                            break;
                        case 'website':
                            $row[] = $user->website ?? '';
                            break;
                        case 'bio':
                            $row[] = $user->bio ?? '';
                            break;
                        case 'created_at':
                            $row[] = $user->created_at->format('Y-m-d H:i:s');
                            break;
                        case 'last_login_at':
                            $row[] = $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : '';
                            break;
                        default:
                            $row[] = '';
                    }
                }
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToJson($users, $fields, $filename)
    {
        $data = $users->map(function($user) use ($fields) {
            $row = [];
            foreach ($fields as $field) {
                switch ($field) {
                    case 'id':
                        $row[$field] = $user->id;
                        break;
                    case 'name':
                        $row[$field] = $user->name;
                        break;
                    case 'email':
                        $row[$field] = $user->email;
                        break;
                    case 'role':
                        $row[$field] = $user->role;
                        break;
                    case 'status':
                        $row[$field] = $user->status;
                        break;
                    case 'phone':
                        $row[$field] = $user->phone;
                        break;
                    case 'location':
                        $row[$field] = $user->location;
                        break;
                    case 'website':
                        $row[$field] = $user->website;
                        break;
                    case 'bio':
                        $row[$field] = $user->bio;
                        break;
                    case 'created_at':
                        $row[$field] = $user->created_at->format('Y-m-d H:i:s');
                        break;
                    case 'last_login_at':
                        $row[$field] = $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : null;
                        break;
                }
            }
            return $row;
        });

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
