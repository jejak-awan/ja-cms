<?php

use App\Modules\User\Models\Permission;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\User;

describe('Permission', function () {
    
    test('creates permission with name and slug', function () {
        $permission = Permission::factory()->create([
            'name' => 'Edit Articles',
            'slug' => 'edit-articles',
        ]);
        
        expect($permission->name)->toBe('Edit Articles')
            ->and($permission->slug)->toBe('edit-articles');
    });
    
    test('has roles relationship', function () {
        $permission = Permission::factory()->create();
        $role = Role::factory()->create();
        
        $permission->roles()->attach($role);
        
        expect($permission->roles)->toHaveCount(1)
            ->and($permission->roles->first()->id)->toBe($role->id);
    });
    
    test('has users relationship', function () {
        $permission = Permission::factory()->create();
        $user = User::factory()->create();
        
        $permission->users()->attach($user);
        
        expect($permission->users)->toHaveCount(1)
            ->and($permission->users->first()->id)->toBe($user->id);
    });
    
    test('groups permissions by module', function () {
        Permission::factory()->create([
            'name' => 'View Articles',
            'group' => 'article',
        ]);
        
        Permission::factory()->create([
            'name' => 'Edit Articles',
            'group' => 'article',
        ]);
        
        Permission::factory()->create([
            'name' => 'View Users',
            'group' => 'user',
        ]);
        
        $articlePermissions = Permission::byGroup('article')->get();
        
        expect($articlePermissions)->toHaveCount(2);
    });
    
    test('scopes by group', function () {
        Permission::factory()->create(['group' => 'article']);
        Permission::factory()->create(['group' => 'article']);
        Permission::factory()->create(['group' => 'user']);
        
        $articlePerms = Permission::byGroup('article')->get();
        $userPerms = Permission::byGroup('user')->get();
        
        expect($articlePerms)->toHaveCount(2)
            ->and($userPerms)->toHaveCount(1);
    });
    
    test('finds permission by name', function () {
        $permission = Permission::factory()->create([
            'name' => 'edit-articles',
        ]);
        
        $found = Permission::findByName('edit-articles');
        
        expect($found)->not->toBeNull()
            ->and($found->id)->toBe($permission->id);
    });
    
    test('returns null when permission not found', function () {
        $found = Permission::findByName('non-existent');
        
        expect($found)->toBeNull();
    });
    
    test('factory creates valid permission', function () {
        $permission = Permission::factory()->create();
        
        expect($permission)->toBeInstanceOf(Permission::class)
            ->and($permission->name)->not->toBeNull()
            ->and($permission->slug)->not->toBeNull()
            ->and($permission->display_name)->not->toBeNull();
    });
});
