<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\Permission;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::latest();

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $roles = $query->paginate(20);
        
        // Cache permissions list for 10 minutes
        $permissions = cache()->remember('admin_permissions_grouped', 600, function() {
            return Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        });

        $stats = [
            'total' => Role::count(),
            'with_users' => Role::whereIn('name', User::distinct()->pluck('role'))->count(),
            'permissions' => Permission::count(),
        ];

        return view('admin.users.roles.index', compact('roles', 'permissions', 'stats'));
    }

    public function create()
    {
        // Cache permissions list for 10 minutes
        $permissions = cache()->remember('admin_permissions_grouped', 600, function() {
            return Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        });
        return view('admin.users.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        // Assign permissions
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully');
    }

    public function show(Role $role)
    {
        $role->load(['users', 'permissions']);
        return view('admin.users.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        // Cache permissions list for 10 minutes
        $permissions = cache()->remember('admin_permissions_grouped', 600, function() {
            return Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        });
        $role->load('permissions');
        return view('admin.users.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        // Sync permissions
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role that has users assigned');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully');
    }

    public function toggleStatus(Role $role)
    {
        $role->update(['is_active' => !$role->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $role->is_active,
            'message' => $role->is_active ? 'Role activated' : 'Role deactivated'
        ]);
    }
}
