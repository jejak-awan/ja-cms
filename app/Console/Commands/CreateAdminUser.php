<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\User\Models\User;
use App\Modules\User\Models\Role;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-user {email=admin@example.com} {password=password123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user for JA-CMS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        
        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return;
        }
        
        // Create admin user
        $user = User::create([
            'name' => 'Administrator',
            'email' => $email,
            'password' => bcrypt($password),
            'role_id' => 1, // Admin role
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        $this->info("Admin user created successfully!");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->info("You can now login at http://localhost:8000/admin");
    }
}
