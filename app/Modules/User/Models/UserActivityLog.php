<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    public static function logActivity(
        ?int $userId,
        string $action,
        ?string $description = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?array $metadata = null,
        string $status = 'success'
    ): self {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'metadata' => $metadata,
            'status' => $status,
        ]);
    }

    public static function logLogin(int $userId, ?string $ipAddress = null): self
    {
        return self::logActivity(
            $userId,
            'login',
            'User logged in',
            $ipAddress
        );
    }

    public static function logLogout(int $userId, ?string $ipAddress = null): self
    {
        return self::logActivity(
            $userId,
            'logout',
            'User logged out',
            $ipAddress
        );
    }

    public static function logProfileUpdate(int $userId, array $changes, ?string $ipAddress = null): self
    {
        return self::logActivity(
            $userId,
            'profile_update',
            'User updated profile information',
            $ipAddress,
            null,
            ['changes' => $changes]
        );
    }

    public static function logPasswordChange(int $userId, ?string $ipAddress = null): self
    {
        return self::logActivity(
            $userId,
            'password_change',
            'User changed password',
            $ipAddress
        );
    }

    public static function logAvatarUpdate(int $userId, ?string $ipAddress = null): self
    {
        return self::logActivity(
            $userId,
            'avatar_update',
            'User updated avatar',
            $ipAddress
        );
    }

    public static function logFailedLogin(string $email, ?string $ipAddress = null): self
    {
        return self::create([
            'user_id' => null, // Use null for failed logins (no user)
            'action' => 'failed_login',
            'description' => "Failed login attempt for email: {$email}",
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => ['email' => $email],
            'status' => 'failed',
        ]);
    }

    public static function logBulkAction(int $userId, string $action, int $affectedUsers, ?string $ipAddress = null): self
    {
        return self::logActivity(
            $userId,
            'bulk_action',
            "Performed bulk action: {$action} on {$affectedUsers} users",
            $ipAddress,
            null,
            ['action' => $action, 'affected_users' => $affectedUsers]
        );
    }

    public static function logRoleChange(int $userId, string $oldRole, string $newRole, ?string $ipAddress = null): self
    {
        return self::logActivity(
            $userId,
            'role_change',
            "User role changed from {$oldRole} to {$newRole}",
            $ipAddress,
            null,
            ['old_role' => $oldRole, 'new_role' => $newRole]
        );
    }

    public static function logStatusChange(int $userId, string $oldStatus, string $newStatus, ?string $ipAddress = null): self
    {
        return self::logActivity(
            $userId,
            'status_change',
            "User status changed from {$oldStatus} to {$newStatus}",
            $ipAddress,
            null,
            ['old_status' => $oldStatus, 'new_status' => $newStatus]
        );
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getFormattedActionAttribute(): string
    {
        return match($this->action) {
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'profile_update' => 'Updated Profile',
            'password_change' => 'Changed Password',
            'avatar_update' => 'Updated Avatar',
            'failed_login' => 'Failed Login',
            'bulk_action' => 'Bulk Action',
            'role_change' => 'Role Changed',
            'status_change' => 'Status Changed',
            default => ucwords(str_replace('_', ' ', $this->action))
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'success' => 'green',
            'failed' => 'red',
            'warning' => 'yellow',
            default => 'gray'
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}