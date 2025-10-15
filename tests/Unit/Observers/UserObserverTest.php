<?php

namespace Tests\Unit\Observers;

use Tests\TestCase;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class UserObserverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_hashes_password_before_save()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'plaintext-password'
        ]);

        $this->assertTrue(Hash::check('plaintext-password', $user->password));
        $this->assertNotEquals('plaintext-password', $user->password);
    }

    /** @test */
    public function it_doesnt_rehash_already_hashed_password()
    {
        $hashedPassword = Hash::make('test-password');
        
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $hashedPassword
        ]);

        $this->assertEquals($hashedPassword, $user->password);
    }

    /** @test */
    public function it_clears_user_cache_when_updated()
    {
        Cache::put('user.cache', 'test data');
        
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $user->update(['name' => 'Updated User']);

        $this->assertFalse(Cache::has('user.cache'));
    }

    /** @test */
    public function it_handles_password_updates_correctly()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'old-password'
        ]);

        $oldPasswordHash = $user->password;

        $user->update(['password' => 'new-password']);

        $this->assertNotEquals($oldPasswordHash, $user->fresh()->password);
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}