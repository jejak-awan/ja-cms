<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    protected $twoFactorService;
    
    public function __construct(TwoFactorAuthService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }
    
    /**
     * Show 2FA setup page
     */
    public function show()
    {
        $user = Auth::user();
        
        if ($user->two_factor_enabled) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Two-factor authentication is already enabled.');
        }
        
        $secret = $this->twoFactorService->generateSecretKey();
        $qrCode = $this->twoFactorService->getQRCodeImage($user, $secret);
        
        return view('admin.security.two-factor', compact('secret', 'qrCode'));
    }
    
    /**
     * Enable 2FA
     */
    public function enable(Request $request): JsonResponse
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|string|size:6',
        ]);
        
        $user = Auth::user();
        
        if ($this->twoFactorService->enableTwoFactor($user, $request->secret, $request->code)) {
            return response()->json([
                'success' => true,
                'message' => 'Two-factor authentication enabled successfully.'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code.'
        ], 422);
    }
    
    /**
     * Disable 2FA
     */
    public function disable(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);
        
        $user = Auth::user();
        
        if ($this->twoFactorService->disableTwoFactor($user, $request->code)) {
            return response()->json([
                'success' => true,
                'message' => 'Two-factor authentication disabled successfully.'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code.'
        ], 422);
    }
    
    /**
     * Show recovery codes
     */
    public function recoveryCodes()
    {
        $user = Auth::user();
        
        if (!$user->two_factor_enabled) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Two-factor authentication is not enabled.');
        }
        
        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        
        return view('admin.security.recovery-codes', compact('recoveryCodes'));
    }
    
    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user->two_factor_enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Two-factor authentication is not enabled.'
            ], 422);
        }
        
        $recoveryCodes = $this->twoFactorService->generateRecoveryCodes();
        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes))
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Recovery codes regenerated successfully.',
            'recovery_codes' => $recoveryCodes
        ]);
    }
    
    /**
     * Verify 2FA code (for login)
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        if ($this->twoFactorService->verifyTwoFactor($user, $request->code)) {
            session(['two_factor_verified' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Two-factor authentication verified successfully.'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code.'
        ], 422);
    }
}
