<?php

namespace App\Services;

use App\Modules\User\Models\User;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FA\Support\Constants;

class TwoFactorAuthService
{
    protected $google2fa;
    
    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }
    
    /**
     * Generate secret key for user
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }
    
    /**
     * Generate QR code URL for Google Authenticator
     */
    public function getQRCodeUrl(User $user, string $secret): string
    {
        $companyName = config('app.name', 'Laravel CMS');
        $companyEmail = $user->email;
        
        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secret
        );
    }
    
    /**
     * Generate QR code image
     */
    public function getQRCodeImage(User $user, string $secret): string
    {
        $qrCodeUrl = $this->getQRCodeUrl($user, $secret);
        
        // Generate QR code using Google Charts API
        $qrCodeData = "otpauth://totp/" . config('app.name', 'Laravel CMS') . ":" . $user->email . "?secret=" . $secret . "&issuer=" . config('app.name', 'Laravel CMS');
        $encodedData = urlencode($qrCodeData);
        
        return '<div style="text-align: center; padding: 20px; border: 1px solid #ddd; background: #f9f9f9;">
                    <p><strong>Scan this QR code with your authenticator app:</strong></p>
                    <img src="https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=' . $encodedData . '" alt="QR Code" style="border: 1px solid #ccc; background: white; padding: 10px;">
                    <p><small>Or manually enter this secret key:</small></p>
                    <p style="font-family: monospace; background: white; padding: 10px; border: 1px solid #ccc; word-break: break-all;">' . $secret . '</p>
                </div>';
    }
    
    /**
     * Verify 2FA code
     */
    public function verifyCode(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }
    
    /**
     * Generate recovery codes
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(substr(md5(uniqid()), 0, 8));
        }
        return $codes;
    }
    
    /**
     * Enable 2FA for user
     */
    public function enableTwoFactor(User $user, string $secret, string $code): bool
    {
        if (!$this->verifyCode($secret, $code)) {
            return false;
        }
        
        $user->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_enabled' => true,
            'two_factor_recovery_codes' => encrypt(json_encode($this->generateRecoveryCodes()))
        ]);
        
        return true;
    }
    
    /**
     * Disable 2FA for user
     */
    public function disableTwoFactor(User $user, string $code): bool
    {
        $secret = decrypt($user->two_factor_secret);
        
        if (!$this->verifyCode($secret, $code)) {
            return false;
        }
        
        $user->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
            'two_factor_recovery_codes' => null
        ]);
        
        return true;
    }
    
    /**
     * Verify 2FA or recovery code
     */
    public function verifyTwoFactor(User $user, string $code): bool
    {
        if (!$user->two_factor_enabled) {
            return true;
        }
        
        $secret = decrypt($user->two_factor_secret);
        
        // Check if it's a recovery code
        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        if (in_array($code, $recoveryCodes)) {
            // Remove used recovery code
            $recoveryCodes = array_diff($recoveryCodes, [$code]);
            $user->update([
                'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes)))
            ]);
            return true;
        }
        
        // Verify 2FA code
        return $this->verifyCode($secret, $code);
    }
}
