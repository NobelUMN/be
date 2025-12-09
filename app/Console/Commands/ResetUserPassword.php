<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    protected $signature = 'user:reset-password';
    protected $description = 'Reset all user passwords with bcrypt hash';

    public function handle()
    {
        $users = [
            ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin'],
            ['username' => 'kasir', 'password' => 'kasir123', 'role' => 'kasir'],
        ];

        foreach ($users as $userData) {
            $user = User::where('username', $userData['username'])->first();
            
            if ($user) {
                $user->password = Hash::make($userData['password']);
                $user->save();
                $this->info("Updated password for: {$userData['username']}");
            } else {
                User::create([
                    'username' => $userData['username'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                ]);
                $this->info("Created user: {$userData['username']}");
            }
        }

        $this->info("\nPasswords reset successfully!");
        $this->info("Admin: admin / admin123");
        $this->info("Kasir: kasir / kasir123");
        
        return 0;
    }
}
