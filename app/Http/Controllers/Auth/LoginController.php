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
            $user = Auth::user();
            if ($user->hasAnyRole(['admin', 'editor', 'author'])) {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/dashboard');
            }
        }
        
        // Auto-detect browser language if not set
        $this->detectBrowserLanguage();
        
        return view('auth.login');
    }

    /**
     * Detect browser language and set locale
     */
    private function detectBrowserLanguage()
    {
        // Skip if locale already set in session
        if (session()->has('locale')) {
            return;
        }

        $browserLanguages = $this->getBrowserLanguages();
        $supportedLanguages = ['id', 'en']; // Supported languages
        $defaultLanguage = 'id'; // Default language

        // Check if browser language is supported
        foreach ($browserLanguages as $lang) {
            if (in_array($lang, $supportedLanguages)) {
                app()->setLocale($lang);
                session(['locale' => $lang]);
                return;
            }
        }

        // Fallback to default language
        app()->setLocale($defaultLanguage);
        session(['locale' => $defaultLanguage]);
    }

    /**
     * Get browser languages from Accept-Language header
     */
    private function getBrowserLanguages()
    {
        $acceptLanguage = request()->header('Accept-Language', '');
        $languages = [];

        if ($acceptLanguage) {
            $parts = explode(',', $acceptLanguage);
            foreach ($parts as $part) {
                $lang = trim(explode(';', $part)[0]);
                // Extract language code (e.g., 'en-US' -> 'en')
                $langCode = explode('-', $lang)[0];
                $languages[] = $langCode;
            }
        }

        return $languages;
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
            'email.required' => __('auth.email_required'),
            'email.email' => __('auth.email_invalid'),
            'password.required' => __('auth.password_required'),
            'password.min' => __('auth.password_min'),
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => __('auth.account_inactive'),
                ])->onlyInput('email');
            }
            
            $request->session()->regenerate();
            
            // Update last login
            $user->updateLastLogin($request->ip());
            
            // Role-based redirect
            if ($user->hasAnyRole(['admin', 'editor', 'author'])) {
                return redirect()->intended('/admin/dashboard')->with('success', __('auth.welcome_admin', ['name' => $user->name]));
            } else {
                return redirect()->intended('/dashboard')->with('success', __('auth.welcome_user', ['name' => $user->name]));
            }
        }

        // Authentication failed
        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', __('auth.logout_success'));
    }

}
