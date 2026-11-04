<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@cinebook.com'],
            [
                'name' => 'Admin CineBook',
                'role' => 'admin',
                'phone' => '081234567890',
                'password' => Hash::make('password123'),
            ]
        );

        // Akun Customer Contoh
        User::firstOrCreate(
            ['email' => 'customer@cinebook.com'],
            [
                'name' => 'John Customer',
                'role' => 'customer',
                'phone' => '089876543210',
                'password' => Hash::make('password123'),
            ]
        );
    }
}