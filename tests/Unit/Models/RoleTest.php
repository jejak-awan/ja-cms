<?php

use App\Modules\User\Models\Role;
use App\Modules\User\Models\Permission;
use App\Modules\User\Models\User;

describe('Role', function () {
    
    test('creates role with name and slug', function () {
        $role = Role::factory()->create([
            'name' => 'Editor',
            'slug' => 'editor',
        ]);
        
        expect($role->name)->toBe('Editor')
            ->and($role->slug)->toBe('editor');
    });
    
    test('has users relationship', function () {
        $role = Role::factory()->create(['name' => 'admin']);
        
        // Note: SQLite uses string 'role' column
        $user = User::factory()->create(['role' => 'admin']);
        
        expect($role->users)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    });
    
    test('a role can have many permissions', function () {
    $role = Role::factory()->create();
    $permission = Permission::factory()->create();
    
    // Attach permission to role
    $role->permissions()->attach($permission->id);
    
    // Test relationship using get() instead of eager loading
    $permissions = $role->permissions()->get();
    
    expect($permissions)->toHaveCount(1)
        ->and($permissions->first()->id)->toBe($permission->id)
        ->and($permissions->first()->name)->toBe($permission->name);
});
    
    test('assigns permission to role', function () {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create(['slug' => 'edit-articles']);
        
        $role->permissions()->attach($permission);
        
        expect($role->permissions()->count())->toBe(1);
    });
    
    test('checks role has permission', function () {
        $role = Role::factory()->create([
            'permissions' => ['edit-articles', 'delete-articles']
        ]);
        
        expect($role->hasPermission('edit-articles'))->toBeTrue()
            ->and($role->hasPermission('manage-users'))->toBeFalse();
    });
    
    test('returns false when no permissions', function () {
        $role = Role::factory()->create([
            'permissions' => null
        ]);
        
        expect($role->hasPermission('any-permission'))->toBeFalse();
    });
    
    test('casts permissions as array', function () {
        $role = Role::factory()->create([
            'permissions' => ['perm1', 'perm2']
        ]);
        
        expect($role->permissions)
            ->toBeArray()
            ->toHaveCount(2);
    });
    
    test('gets default permissions list', function () {
        $permissions = Role::getDefaultPermissions();
        
        expect($permissions)
            ->toBeArray()
            ->toContain('view_dashboard')
            ->toContain('manage_articles')
            ->toContain('manage_users');
    });
    
    test('factory creates valid role', function () {
        $role = Role::factory()->create();
        
        expect($role)->toBeInstanceOf(Role::class)
            ->and($role->name)->not->toBeNull()
            ->and($role->slug)->not->toBeNull();
    });
    
    test('removes permission from role', function () {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();
        
        $role->permissions()->attach($permission);
        expect($role->permissions()->count())->toBe(1);
        
        $role->permissions()->detach($permission);
        expect($role->permissions()->count())->toBe(0);
    });
});
