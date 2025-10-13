<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'group',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permission'
        )->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_permission'
        )->withTimestamps();
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    // ========================================
    // STATIC METHODS
    // ========================================

    public static function findByName(string $name): ?Permission
    {
        return self::where('name', $name)->first();
    }

    public static function getGroups(): array
    {
        return self::whereNotNull('group')
            ->distinct()
            ->pluck('group')
            ->toArray();
    }

    public static function getByGroup(string $group): array
    {
        return self::byGroup($group)->get()->toArray();
    }
}
