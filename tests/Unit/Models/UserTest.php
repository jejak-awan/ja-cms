<?php

use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\Permission;
use App\Modules\Article\Models\Article;
use Illuminate\Support\Facades\Hash;

describe('User Model', function () {
    
    test('has correct fillable attributes', function () {
        $user = new User();
        
        $fillable = $user->getFillable();
        
        expect($fillable)->toContain('name', 'email', 'password', 'status');
    });
    
    test('password is hashed when set', function () {
        $user = User::factory()->create(['password' => 'password123']);
        
        expect(Hash::check('password123', $user->password))->toBeTrue();
    });
    
    test('can have role', function () {
        $role = Role::factory()->create();
        $user = User::factory()->create();
        
        $user->roles()->attach($role->id);
        
        expect($user->roles)->toHaveCount(1);
        expect($user->roles->first())->toBeInstanceOf(Role::class);
    });
    
    test('can have multiple roles', function () {
        $roles = Role::factory()->count(3)->create();
        $user = User::factory()->create();
        
        $user->roles()->attach($roles->pluck('id'));
        
        expect($user->roles)->toHaveCount(3);
    });
    
    test('can have direct permissions', function () {
        $permission = Permission::factory()->create();
        $user = User::factory()->create();
        
        $user->permissions()->attach($permission->id);
        
        expect($user->permissions)->toHaveCount(1);
        expect($user->permissions->first())->toBeInstanceOf(Permission::class);
    });
    
    test('can check if has role', function () {
        $role = Role::factory()->create(['slug' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($role->id);
        
        expect($user->hasRole('admin'))->toBeTrue();
        expect($user->hasRole('editor'))->toBeFalse();
    });
    
    test('can check if has permission through role', function () {
        $permission = Permission::factory()->create(['slug' => 'articles.create']);
        $role = Role::factory()->create();
        $role->permissions()->attach($permission->id);
        
        $user = User::factory()->create();
        $user->roles()->attach($role->id);
        
        expect($user->hasPermission('articles.create'))->toBeTrue();
    });
    
    test('can check if has direct permission', function () {
        $permission = Permission::factory()->create(['slug' => 'articles.delete']);
        $user = User::factory()->create();
        $user->permissions()->attach($permission->id);
        
        expect($user->hasPermission('articles.delete'))->toBeTrue();
    });
    
    test('active scope returns only active users', function () {
        User::factory()->count(3)->create(['status' => 'active']);
        User::factory()->count(2)->create(['status' => 'inactive']);
        
        $active = User::active()->get();
        
        expect($active->count())->toBeGreaterThanOrEqual(3);
        expect($active->every(fn($user) => $user->status === 'active'))->toBeTrue();
    });
    
    test('inactive scope returns only inactive users', function () {
        User::factory()->count(2)->create(['status' => 'inactive']);
        User::factory()->count(3)->create(['status' => 'active']);
        
        $inactive = User::inactive()->get();
        
        expect($inactive->count())->toBeGreaterThanOrEqual(2);
        expect($inactive->every(fn($user) => $user->status === 'inactive'))->toBeTrue();
    });
    
    test('can have articles', function () {
        $user = User::factory()->create();
        $articles = Article::factory()->count(5)->create(['user_id' => $user->id]);
        
        expect($user->articles)->toHaveCount(5);
        expect($user->articles->first())->toBeInstanceOf(Article::class);
    });
    
    test('full_name accessor returns correct value', function () {
        $user = User::factory()->create(['name' => 'John Doe']);
        
        expect($user->full_name)->toBe('John Doe');
    });
    
    test('is_active accessor returns correct boolean', function () {
        $active = User::factory()->create(['status' => 'active']);
        $inactive = User::factory()->create(['status' => 'inactive']);
        
        expect($active->is_active)->toBeTrue();
        expect($inactive->is_active)->toBeFalse();
    });
    
    test('search scope finds users by name or email', function () {
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);
        
        $results = User::search('john')->get();
        
        expect($results->count())->toBeGreaterThanOrEqual(1);
    });
    
    test('can assign role', function () {
        $role = Role::factory()->create();
        $user = User::factory()->create();
        
        $user->assignRole($role);
        
        expect($user->hasRole($role->slug))->toBeTrue();
    });
    
    test('can remove role', function () {
        $role = Role::factory()->create();
        $user = User::factory()->create();
        $user->roles()->attach($role->id);
        
        $user->removeRole($role);
        
        expect($user->fresh()->hasRole($role->slug))->toBeFalse();
    });
    
    test('can give permission', function () {
        $permission = Permission::factory()->create();
        $user = User::factory()->create();
        
        $user->givePermissionTo($permission);
        
        expect($user->hasPermission($permission->slug))->toBeTrue();
    });
});
