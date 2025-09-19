<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Create or update admin user
            $admin = User::updateOrCreate(
                ['email' => 'admin@pagsuronglagonoy.com'],
                [
                    'name' => 'Site Admin',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'role' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Only create profile if it doesn't exist
            if (!Profile::where('user_id', $admin->id)->exists()) {
                Profile::create([
                    'user_id' => $admin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}


