<?php

namespace Database\Seeders;

use App\Models\Toda;
use Illuminate\Database\Seeder;

class TodaSeeder extends Seeder
{
    public function run(): void
    {
        $todas = [
            [
                'name' => 'MADTODA',
                'president' => 'Juan Dela Cruz',
                'registration_date' => now(),
                'description' => 'Maramag Drivers TODA Association',
                'status' => 'active'
            ],
            [
                'name' => 'MATODA',
                'president' => 'Pedro Santos',
                'registration_date' => now(),
                'description' => 'Maramag TODA Association',
                'status' => 'active'
            ]
        ];

        foreach ($todas as $toda) {
            Toda::create($toda);
        }
    }
} 