<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Modules\Admin\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddlewareTest extends TestCase
{
    /** @test */
    public function it_allows_authenticated_admin_user()
    {
        // Mock user with admin role
        $user = $this->createMockUser(['admin']);
        
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('Admin access granted');
        });
        
        $this->assertEquals('Admin access granted', $response->getContent());
    }

    /** @test */
    public function it_allows_authenticated_editor_user()
    {
        // Mock user with editor role
        $user = $this->createMockUser(['editor']);
        
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('Admin access granted');
        });
        
        $this->assertEquals('Admin access granted', $response->getContent());
    }

    /** @test */
    public function it_allows_authenticated_author_user()
    {
        // Mock user with author role
        $user = $this->createMockUser(['author']);
        
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('Admin access granted');
        });
        
        $this->assertEquals('Admin access granted', $response->getContent());
    }

    /** @test */
    public function it_redirects_unauthenticated_user()
    {
        Auth::shouldReceive('check')->andReturn(false);
        
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('Should not reach here');
        });
        
        $this->assertTrue($response->isRedirect());
        $this->assertEquals(route('admin.login'), $response->headers->get('Location'));
    }

    /** @test */
    public function it_blocks_user_without_admin_role()
    {
        // Mock user with regular user role
        $user = $this->createMockUser(['user']);
        
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/test');
        
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->expectExceptionMessage('Unauthorized access to admin panel.');
        
        $middleware->handle($request, function ($req) {
            return response('Should not reach here');
        });
    }

    /** @test */
    public function it_blocks_user_without_any_role()
    {
        // Mock user with no roles
        $user = $this->createMockUser([]);
        
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/test');
        
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->expectExceptionMessage('Unauthorized access to admin panel.');
        
        $middleware->handle($request, function ($req) {
            return response('Should not reach here');
        });
    }

    /** @test */
    public function it_handles_multiple_admin_roles()
    {
        // Mock user with multiple admin roles
        $user = $this->createMockUser(['admin', 'editor']);
        
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('Admin access granted');
        });
        
        $this->assertEquals('Admin access granted', $response->getContent());
    }

    /** @test */
    public function it_returns_403_for_unauthorized_roles()
    {
        // Mock user with unauthorized role
        $user = $this->createMockUser(['moderator']);
        
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/test');
        
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->expectExceptionMessage('Unauthorized access to admin panel.');
        
        $middleware->handle($request, function ($req) {
            return response('Should not reach here');
        });
    }

    /** @test */
    public function it_passes_request_to_next_middleware_on_success()
    {
        // Mock user with admin role
        $user = $this->createMockUser(['admin']);
        
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);
        
        $middleware = new AdminMiddleware();
        $request = Request::create('/admin/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('Admin access granted');
        });
        
        // Should reach the route handler
        $this->assertEquals('Admin access granted', $response->getContent());
    }

    /**
     * Create a mock user with specified roles
     */
    private function createMockUser(array $roles)
    {
        $user = $this->createMock(\App\Modules\User\Models\User::class);
        $user->method('hasAnyRole')->willReturnCallback(function ($allowedRoles) use ($roles) {
            return !empty(array_intersect($roles, $allowedRoles));
        });
        
        return $user;
    }
}