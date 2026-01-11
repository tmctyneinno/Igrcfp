<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@igrcfp.org',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@igrcfp.org',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        Admin::create([
            'name' => 'Content Moderator',
            'email' => 'moderator@igrcfp.org',
            'password' => Hash::make('password123'),
            'role' => 'moderator',
        ]);
    }
}