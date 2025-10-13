<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // Redirect if already authenticated
        if (Auth::check()) {
            return redirect('/admin');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // TODO: Log successful login to activity_logs table
            // Will be implemented when Activity Log module is ready
            
            return redirect()->intended('/admin')->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        // TODO: Log the logout activity to activity_logs table
        // Will be implemented when Activity Log module is ready

        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'You have been successfully logged out.');
    }
}
