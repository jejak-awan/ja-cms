<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Writer;

class ImportExportController extends Controller
{
    public function index()
    {
        return view('admin.users.import-export.index');
    }

    public function export(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'format' => 'required|string|in:csv,json,xlsx',
            'fields' => 'array',
            'fields.*' => 'string|in:id,name,email,role,status,phone,location,website,bio,created_at,last_login_at',
            'role_filter' => 'nullable|string|exists:roles,name',
            'status_filter' => 'nullable|string|in:active,inactive,suspended',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = User::query();

        // Apply filters
        if ($request->filled('role_filter')) {
            $query->where('role', $request->role_filter);
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->get();
        $format = $request->format;
        $fields = $request->fields ?? ['id', 'name', 'email', 'role', 'status', 'created_at'];

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No users found to export'
            ], 404);
        }

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.' . $format;

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($users, $fields, $filename);
            case 'json':
                return $this->exportToJson($users, $fields, $filename);
            case 'xlsx':
                return $this->exportToXlsx($users, $fields, $filename);
            default:
                return response()->json(['error' => 'Unsupported format'], 400);
        }
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240', // 10MB max
            'update_existing' => 'boolean',
            'send_welcome_email' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');
        $updateExisting = $request->boolean('update_existing', false);
        $sendWelcomeEmail = $request->boolean('send_welcome_email', false);

        try {
            $results = $this->processImportFile($file, $updateExisting, $sendWelcomeEmail);

            return response()->json([
                'success' => true,
                'message' => 'Import completed successfully',
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadTemplate(Request $request)
    {
        $format = $request->get('format', 'csv');
        $filename = 'users_import_template.' . $format;

        $templateData = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'role' => 'author',
                'status' => 'active',
                'phone' => '+1234567890',
                'location' => 'New York, USA',
                'website' => 'https://johndoe.com',
                'bio' => 'Sample bio text'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'role' => 'editor',
                'status' => 'active',
                'phone' => '+1234567891',
                'location' => 'Los Angeles, USA',
                'website' => 'https://janesmith.com',
                'bio' => 'Another sample bio'
            ]
        ];

        switch ($format) {
            case 'csv':
                return $this->exportToCsv(collect($templateData), array_keys($templateData[0]), $filename);
            case 'json':
                return $this->exportToJson(collect($templateData), array_keys($templateData[0]), $filename);
            default:
                return response()->json(['error' => 'Unsupported format'], 400);
        }
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

    private function exportToXlsx($users, $fields, $filename)
    {
        // For XLSX export, we'll use CSV format for now
        // In a real application, you might want to use PhpSpreadsheet
        return $this->exportToCsv($users, $fields, str_replace('.xlsx', '.csv', $filename));
    }

    private function processImportFile($file, $updateExisting, $sendWelcomeEmail)
    {
        $extension = $file->getClientOriginalExtension();
        $results = [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => []
        ];

        try {
            DB::beginTransaction();

            if (in_array($extension, ['csv', 'txt'])) {
                $this->processCsvFile($file, $updateExisting, $sendWelcomeEmail, $results);
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                $this->processExcelFile($file, $updateExisting, $sendWelcomeEmail, $results);
            } else {
                throw new \Exception('Unsupported file format');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    private function processCsvFile($file, $updateExisting, $sendWelcomeEmail, &$results)
    {
        $csv = Reader::createFromPath($file->getPathname());
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach ($records as $index => $record) {
            $results['total']++;
            $rowNumber = $index + 2; // +2 because header is row 1 and array is 0-indexed

            try {
                $this->processUserRecord($record, $updateExisting, $sendWelcomeEmail, $results, $rowNumber);
            } catch (\Exception $e) {
                $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                $results['skipped']++;
            }
        }
    }

    private function processExcelFile($file, $updateExisting, $sendWelcomeEmail, &$results)
    {
        // For Excel files, we'll use a simple approach
        // In a real application, you might want to use PhpSpreadsheet
        throw new \Exception('Excel import not implemented yet. Please use CSV format.');
    }

    private function processUserRecord($record, $updateExisting, $sendWelcomeEmail, &$results, $rowNumber)
    {
        // Validate required fields
        if (empty($record['name']) || empty($record['email'])) {
            throw new \Exception('Name and email are required');
        }

        // Validate email format
        if (!filter_var($record['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format');
        }

        // Validate role
        $validRoles = Role::pluck('name')->toArray();
        $role = $record['role'] ?? 'subscriber';
        if (!in_array($role, $validRoles)) {
            throw new \Exception('Invalid role. Valid roles: ' . implode(', ', $validRoles));
        }

        // Validate status
        $status = $record['status'] ?? 'active';
        if (!in_array($status, ['active', 'inactive', 'suspended'])) {
            throw new \Exception('Invalid status. Valid statuses: active, inactive, suspended');
        }

        // Check if user exists
        $existingUser = User::where('email', $record['email'])->first();

        if ($existingUser) {
            if ($updateExisting) {
                $existingUser->update([
                    'name' => $record['name'],
                    'role' => $role,
                    'status' => $status,
                    'phone' => $record['phone'] ?? null,
                    'location' => $record['location'] ?? null,
                    'website' => $record['website'] ?? null,
                    'bio' => $record['bio'] ?? null,
                ]);
                $results['updated']++;
            } else {
                $results['skipped']++;
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $record['name'],
                'email' => $record['email'],
                'password' => Hash::make(Str::random(12)), // Generate random password
                'role' => $role,
                'status' => $status,
                'phone' => $record['phone'] ?? null,
                'location' => $record['location'] ?? null,
                'website' => $record['website'] ?? null,
                'bio' => $record['bio'] ?? null,
                'email_verified_at' => now(),
            ]);

            $results['created']++;

            // Log the import activity
            \App\Modules\User\Models\UserActivityLog::logActivity(
                auth()->id(),
                'user_import',
                "User imported via bulk import: {$user->name}",
                null,
                null,
                ['imported_user_id' => $user->id, 'imported_user_email' => $user->email]
            );
        }
    }
}
