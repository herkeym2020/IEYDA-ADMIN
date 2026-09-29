<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates a default super admin for testing.
     * Login: admin@ieyda.com / admin123
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ieyda.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
