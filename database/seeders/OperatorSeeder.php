<?php

namespace Database\Seeders;

use App\Models\Operator;
use App\Models\EmergencyContact;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        $operators = [
            [
                'last_name' => 'Garcia',
                'first_name' => 'Ramon',
                'middle_name' => 'Santos',
                'email' => 'ramon.garcia@example.com',
                'toda_id' => 1,
                'status' => 'active',
                'address' => 'Poblacion, Maramag, Bukidnon',
                'contact_no' => '09123456789',
                'emergency_contact' => [
                    'contact_person' => 'Maria Garcia',
                    'tel_no' => '09987654321'
                ]
            ],
            [
                'last_name' => 'Santos',
                'first_name' => 'Miguel',
                'middle_name' => 'Reyes',
                'email' => 'miguel.santos@example.com',
                'toda_id' => 2,
                'status' => 'active',
                'address' => 'Base Camp, Maramag, Bukidnon',
                'contact_no' => '09234567890',
                'emergency_contact' => [
                    'contact_person' => 'Ana Santos',
                    'tel_no' => '09876543210'
                ]
            ]
        ];

        foreach ($operators as $operatorData) {
            $emergencyContact = $operatorData['emergency_contact'];
            unset($operatorData['emergency_contact']);
            
            $operator = Operator::create($operatorData);
            
            EmergencyContact::create([
                'operator_id' => $operator->id,
                'contact_person' => $emergencyContact['contact_person'],
                'tel_no' => $emergencyContact['tel_no']
            ]);
        }
    }
} 