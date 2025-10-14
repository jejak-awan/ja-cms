<?php

use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;

describe('UserObserver', function () {
    
    describe('creating event', function () {
        
        test('hashes password when creating user', function () {
            $plainPassword = 'password123';
            
            $user = User::factory()->make([
                'password' => $plainPassword,
            ]);
            
            $user->save();
            
            expect($user->password)
                ->not->toBe($plainPassword)
                ->and(Hash::check($plainPassword, $user->password))->toBeTrue();
        });
        
        test('does not rehash already hashed password', function () {
            $hashedPassword = Hash::make('password123');
            
            $user = User::factory()->make([
                'password' => $hashedPassword,
            ]);
            
            $user->save();
            
            expect($user->password)->toBe($hashedPassword);
        });
        
        test('sets default status to active when not provided', function () {
            $user = User::factory()->make([
                'status' => null,
            ]);
            
            $user->save();
            
            expect($user->status)->toBe('active');
        });
    });
    
    describe('updating event', function () {
        
        test('hashes password when updating', function () {
            $user = User::factory()->create();
            
            $newPassword = 'newpassword123';
            $user->password = $newPassword;
            $user->save();
            
            expect($user->password)
                ->not->toBe($newPassword)
                ->and(Hash::check($newPassword, $user->password))->toBeTrue();
        });
        
        test('does not rehash password if not changed', function () {
            $user = User::factory()->create();
            
            $originalPassword = $user->password;
            
            $user->name = 'Updated Name';
            $user->save();
            
            expect($user->password)->toBe($originalPassword);
        });
        
        test('does not rehash already hashed password on update', function () {
            $user = User::factory()->create();
            
            $hashedPassword = Hash::make('password123');
            $user->password = $hashedPassword;
            $user->save();
            
            expect($user->password)->toBe($hashedPassword);
        });
    });
});
