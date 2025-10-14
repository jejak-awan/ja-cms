<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\Permission;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::withCount(['roles', 'users'])->latest();

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('display_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by group
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        $permissions = $query->paginate(20);
        $groups = Permission::getGroups();

        $stats = [
            'total' => Permission::count(),
            'groups' => count($groups),
            'with_roles' => Permission::has('roles')->count(),
        ];

        return view('admin.users.permissions.index', compact('permissions', 'groups', 'stats'));
    }

    public function create()
    {
        $groups = Permission::getGroups();
        return view('admin.users.permissions.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'nullable|string|max:100',
        ]);

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'group' => $request->group,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully');
    }

    public function edit(Permission $permission)
    {
        $groups = Permission::getGroups();
        return view('admin.users.permissions.edit', compact('permission', 'groups'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'nullable|string|max:100',
        ]);

        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'group' => $request->group,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to roles or users
        if ($permission->roles()->count() > 0 || $permission->users()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Cannot delete permission that is assigned to roles or users');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}
