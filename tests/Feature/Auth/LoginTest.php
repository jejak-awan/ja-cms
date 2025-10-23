<?php

use App\Modules\User\Models\User;

describe('Authentication', function () {
    
    test('login page loads successfully', function () {
        $response = $this->get(route('admin.login'));
        
        // Admin login now redirects to unified login
        $response->assertRedirect(route('login'));
    });
    
    test('user can login with valid credentials', function () {
        $user = createAdmin();
        $user->update([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        
        $response = $this->post(route('login.post'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    });
    
    test('user cannot login with invalid credentials', function () {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        
        $response = $this->post(route('login.post'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);
        
        $response->assertRedirect();
        $this->assertGuest();
    });
    
    test('inactive user cannot login', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'status' => 'inactive',
        ]);
        
        $response = $this->post(route('login.post'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        
        $this->assertGuest();
    });
    
    test('user can logout', function () {
        $user = createAdmin();
        $this->actingAs($user);
        
        $response = $this->post(route('admin.logout'));
        
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });
    
    test('guest cannot access admin dashboard', function () {
        $response = $this->get(route('admin.dashboard'));
        
        $response->assertRedirect(route('admin.login'));
    });
    
    test('authenticated user can access admin dashboard', function () {
        $user = createAdmin();
        $this->actingAs($user);
        
        $response = $this->get(route('admin.dashboard'));
        
        $response->assertOk();
    });
});
