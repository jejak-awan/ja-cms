<?php

namespace App\Modules\User\Models;

use App\Modules\User\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    public function activityLogs()
    {
        return $this->hasMany(\App\Modules\User\Models\UserActivityLog::class);
    }
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'status',
        'last_login_at',
        'last_login_ip',
        'bio',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function articles()
    {
        return $this->hasMany(\App\Modules\Article\Models\Article::class);
    }

    public function pages()
    {
        return $this->hasMany(\App\Modules\Page\Models\Page::class);
    }

    public function media()
    {
        return $this->hasMany(\App\Modules\Media\Models\Media::class);
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'user_role'
        )->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'user_permission'
        )->withTimestamps();
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeEditors($query)
    {
        return $query->where('role', 'editor');
    }

    public function scopeAuthors($query)
    {
        return $query->where('role', 'author');
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%")
              ->orWhere('bio', 'LIKE', "%{$term}%");
        });
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    // ========================================
    // ACCESSORS
    // ========================================

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::disk('public')->url($this->avatar);
        }

        // Generate avatar from Gravatar
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    public function getIsVerifiedAttribute(): bool
    {
        return !is_null($this->email_verified_at);
    }

    // ========================================
    // ROLE & PERMISSION METHODS
    // ========================================

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return $this->roles()->whereIn('slug', $roles)->exists();
        }

        return $this->roles()->where('slug', $roles)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }

    public function hasPermission(string $permission): bool
    {
        // Admin has all permissions
        if ($this->is_admin) {
            return true;
        }

        // Check direct permissions
        if ($this->permissions()->where('slug', $permission)->exists()) {
            return true;
        }

        // Check role permissions
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('slug', $permission);
            })
            ->exists();
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    public function assignRole(Role|string $role): bool
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        if ($this->roles()->where('roles.id', $role->id)->exists()) {
            return true;
        }

        $this->roles()->attach($role->id);
        return true;
    }

    public function removeRole(Role|string $role): bool
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        $this->roles()->detach($role->id);
        return true;
    }

    public function syncRoles(array $roles): void
    {
        $roleIds = Role::whereIn('name', $roles)->pluck('id')->toArray();
        $this->roles()->sync($roleIds);
    }

    public function givePermissionTo(Permission|string $permission): bool
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        if ($this->permissions()->where('permissions.id', $permission->id)->exists()) {
            return true;
        }

        $this->permissions()->attach($permission->id);
        return true;
    }

    public function revokePermissionTo(Permission|string $permission): bool
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        $this->permissions()->detach($permission->id);
        return true;
    }

    public function syncPermissions(array $permissions): void
    {
        $permissionIds = Permission::whereIn('name', $permissions)->pluck('id')->toArray();
        $this->permissions()->sync($permissionIds);
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    public function activate(): bool
    {
        return $this->update(['status' => 'active']);
    }

    public function deactivate(): bool
    {
        return $this->update(['status' => 'inactive']);
    }

    public function suspend(): bool
    {
        return $this->update(['status' => 'suspended']);
    }

    public function markEmailAsVerified(): bool
    {
        return $this->update(['email_verified_at' => now()]);
    }

    public function updateLastLogin(?string $ipAddress = null): bool
    {
        return $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress ?? request()->ip(),
        ]);
    }

    public function canAccessAdmin(): bool
    {
        return in_array($this->role, ['admin', 'editor', 'author']);
    }

    public function canEditArticle(\App\Modules\Article\Models\Article $article): bool
    {
        if ($this->is_admin) {
            return true;
        }

        if ($this->role === 'editor') {
            return true;
        }

        if ($this->role === 'author' && $article->user_id === $this->id) {
            return true;
        }

        return false;
    }

    public function canDeleteArticle(\App\Modules\Article\Models\Article $article): bool
    {
        if ($this->is_admin) {
            return true;
        }

        if ($this->role === 'editor') {
            return true;
        }

        return false;
    }
}

