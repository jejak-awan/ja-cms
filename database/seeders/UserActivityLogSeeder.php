<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserActivityLog;
use Carbon\Carbon;

class UserActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $logs = [
            [
                'user_id' => $user->id,
                'action' => 'login',
                'description' => 'User logged in',
                'status' => 'success',
                'ip_address' => '192.168.88.44',
                'created_at' => Carbon::parse('2025-10-13 08:00:00'),
            ],
            [
                'user_id' => $user->id,
                'action' => 'update_profile',
                'description' => 'Profile updated',
                'status' => 'success',
                'ip_address' => '192.168.88.44',
                'created_at' => Carbon::parse('2025-10-14 09:30:00'),
            ],
            [
                'user_id' => $user->id,
                'action' => 'logout',
                'description' => 'User logged out',
                'status' => 'success',
                'ip_address' => '192.168.88.44',
                'created_at' => Carbon::parse('2025-10-14 10:00:00'),
            ],
        ];

        foreach ($logs as $log) {
            UserActivityLog::create($log);
        }
    }
}
