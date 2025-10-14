<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions'
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

    public function users()
    {
        // SQLite uses 'role' string, not 'role_id'
        return $this->hasMany(User::class, 'role', 'name');
    }

    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return false;
        }
        return in_array($permission, $this->permissions);
    }

    public static function getDefaultPermissions()
    {
        return [
            'view_dashboard',
            'manage_articles',
            'manage_categories',
            'manage_pages',
            'manage_media',
            'manage_menus',
            'manage_users',
            'manage_roles',
            'manage_settings',
            'manage_themes',
            'manage_plugins',
        ];
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }
}
