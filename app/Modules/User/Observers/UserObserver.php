<?php

namespace App\Modules\User\Observers;

use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function creating(User $user): void
    {
        // Hash password if it's not already hashed
        if ($user->password && !str_starts_with($user->password, '$2y$')) {
            $user->password = Hash::make($user->password);
        }

        // Set default role if not set
        if (empty($user->role)) {
            $user->role = 'user';
        }

        // Set default status if not set
        if (empty($user->status)) {
            $user->status = 'active';
        }
    }

    public function updating(User $user): void
    {
        // Hash password if it's changed and not already hashed
        if ($user->isDirty('password') && $user->password && !str_starts_with($user->password, '$2y$')) {
            $user->password = Hash::make($user->password);
        }
    }

    public function updated(User $user): void
    {
        // Clear user cache when updated
        Cache::forget('user.cache');
        Cache::forget("user_profile_{$user->id}");
    }
}
