<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = [
            [
                'operator_id' => 1,
                'last_name' => 'Cruz',
                'first_name' => 'Jose',
                'middle_name' => 'Reyes',
                'address' => 'Anahawon, Maramag, Bukidnon',
                'contact_no' => '09123456789',
                'drivers_license_no' => 'N01-12-345678',
                'license_expiry_date' => now()->addYears(3)
            ],
            [
                'operator_id' => 1,
                'last_name' => 'Reyes',
                'first_name' => 'Antonio',
                'middle_name' => 'Santos',
                'address' => 'Base Camp, Maramag, Bukidnon',
                'contact_no' => '09234567890',
                'drivers_license_no' => 'N01-12-345679',
                'license_expiry_date' => now()->addYears(3)
            ],
            [
                'operator_id' => 2,
                'last_name' => 'Santos',
                'first_name' => 'Pedro',
                'middle_name' => 'Garcia',
                'address' => 'Poblacion, Maramag, Bukidnon',
                'contact_no' => '09345678901',
                'drivers_license_no' => 'N01-12-345680',
                'license_expiry_date' => now()->addYears(3)
            ]
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
} 