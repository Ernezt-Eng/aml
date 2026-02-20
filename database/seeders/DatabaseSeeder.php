<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\FaultReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $tech1 = User::create([
            'name' => 'John Technician',
            'email' => 'tech1@example.com',
            'password' => Hash::make('password'),
            'role' => 'technician',
        ]);

        $tech2 = User::create([
            'name' => 'Jane Technician',
            'email' => 'tech2@example.com',
            'password' => Hash::make('password'),
            'role' => 'technician',
        ]);

        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create Assets
        $assets = [
            [
                'name' => 'HVAC System - Building A',
                'asset_code' => 'HVAC-001',
                'category' => 'HVAC',
                'location' => 'Building A - Roof',
                'purchase_date' => '2020-03-15',
                'warranty_expiry' => '2025-03-15',
                'status' => 'operational',
                'description' => 'Central HVAC system for Building A',
            ],
            [
                'name' => 'Elevator - Main Lobby',
                'asset_code' => 'ELEV-001',
                'category' => 'Elevator',
                'location' => 'Main Lobby',
                'purchase_date' => '2019-06-10',
                'warranty_expiry' => '2024-06-10',
                'status' => 'operational',
                'description' => 'Main passenger elevator',
            ],
            [
                'name' => 'Generator - Backup Power',
                'asset_code' => 'GEN-001',
                'category' => 'Generator',
                'location' => 'Basement - Power Room',
                'purchase_date' => '2021-01-20',
                'warranty_expiry' => '2026-01-20',
                'status' => 'operational',
                'description' => 'Emergency backup generator - 500kW',
            ],
            [
                'name' => 'Fire Suppression System',
                'asset_code' => 'FIRE-001',
                'category' => 'Safety',
                'location' => 'Building-wide',
                'purchase_date' => '2018-09-05',
                'warranty_expiry' => '2023-09-05',
                'status' => 'operational',
                'description' => 'Automated fire suppression and sprinkler system',
            ],
            [
                'name' => 'Chiller Unit - North Wing',
                'asset_code' => 'CHILL-001',
                'category' => 'HVAC',
                'location' => 'North Wing - Mechanical Room',
                'purchase_date' => '2022-04-12',
                'warranty_expiry' => '2027-04-12',
                'status' => 'maintenance',
                'description' => 'Industrial chiller unit for north wing cooling',
            ],
        ];

        foreach ($assets as $assetData) {
            Asset::create($assetData);
        }

        // Create Fault Reports
        $faults = [
            [
                'asset_id' => 1,
                'reported_by' => $user->id,
                'assigned_to' => $tech1->id,
                'title' => 'Unusual noise from HVAC compressor',
                'description' => 'The HVAC system is making a grinding noise, especially during startup. Need immediate inspection.',
                'priority' => 'high',
                'status' => 'in_progress',
                'started_at' => now()->subDays(2),
            ],
            [
                'asset_id' => 2,
                'reported_by' => $user->id,
                'assigned_to' => $tech2->id,
                'title' => 'Elevator door not closing properly',
                'description' => 'The elevator door on floor 3 is not closing smoothly. Sometimes requires manual assistance.',
                'priority' => 'critical',
                'status' => 'completed',
                'started_at' => now()->subDays(5),
                'completed_at' => now()->subDays(3),
                'closure_notes' => 'Replaced door sensor and lubricated door tracks. Tested multiple times - working properly now.',
            ],
            [
                'asset_id' => 3,
                'reported_by' => $admin->id,
                'assigned_to' => null,
                'title' => 'Generator weekly test failed',
                'description' => 'During routine weekly test, generator failed to start. Battery voltage seems low.',
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'asset_id' => 4,
                'reported_by' => $user->id,
                'assigned_to' => $tech1->id,
                'title' => 'Fire alarm panel showing fault',
                'description' => 'Panel indicates fault in zone 3. Need to investigate and test.',
                'priority' => 'critical',
                'status' => 'closed',
                'started_at' => now()->subDays(10),
                'completed_at' => now()->subDays(8),
                'closed_at' => now()->subDays(7),
                'closure_notes' => 'False alarm due to dust accumulation on sensor. Cleaned sensor, tested system, all zones operational.',
            ],
            [
                'asset_id' => 5,
                'reported_by' => $admin->id,
                'assigned_to' => $tech2->id,
                'title' => 'Chiller not reaching target temperature',
                'description' => 'North wing chiller is running but not achieving the set temperature of 45°F. Currently at 52°F.',
                'priority' => 'medium',
                'status' => 'in_progress',
                'started_at' => now()->subDays(1),
            ],
            [
                'asset_id' => 1,
                'reported_by' => $user->id,
                'assigned_to' => $tech1->id,
                'title' => 'Filter replacement needed',
                'description' => 'Regular maintenance - HVAC filters need replacement per maintenance schedule.',
                'priority' => 'low',
                'status' => 'closed',
                'started_at' => now()->subDays(15),
                'completed_at' => now()->subDays(14),
                'closed_at' => now()->subDays(14),
                'closure_notes' => 'Replaced all filters. System airflow restored to normal levels.',
            ],
        ];

        foreach ($faults as $faultData) {
            FaultReport::create($faultData);
        }
    }
}
