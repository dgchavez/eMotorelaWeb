<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@emotorelaweb.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'contact_no' => '09123456789',
            'address' => 'Maramag, Bukidnon'
        ]);

        // Create a staff user
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@emotorelaweb.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'first_name' => 'Staff',
            'last_name' => 'User',
            'contact_no' => '09987654321',
            'address' => 'Maramag, Bukidnon'
        ]);
    }
} 