<?php

namespace Database\Seeders;

use App\Models\Fleet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fleets = [
            [
                'fleet_number' => 'TRK001',
                'vehicle_type' => 'truck',
                'capacity' => 10.00,
                'driver_name' => 'John Doe',
                'driver_phone' => '081234567890',
                'current_latitude' => -6.2088,
                'current_longitude' => 106.8456,
                'last_location_update' => now(),
            ],
            [
                'fleet_number' => 'VAN001',
                'vehicle_type' => 'van',
                'capacity' => 2.50,
                'driver_name' => 'Jane Smith',
                'driver_phone' => '081234567891',
                'current_latitude' => -6.1745,
                'current_longitude' => 106.8227,
                'last_location_update' => now(),
            ],
            [
                'fleet_number' => 'MTR001',
                'vehicle_type' => 'motorcycle',
                'capacity' => 0.50,
                'driver_name' => 'Bob Wilson',
                'driver_phone' => '081234567892',
                'current_latitude' => -6.2297,
                'current_longitude' => 106.6892,
                'last_location_update' => now(),
            ],
            [
                'fleet_number' => 'CON001',
                'vehicle_type' => 'container',
                'capacity' => 25.00,
                'driver_name' => 'Mike Johnson',
                'driver_phone' => '081234567893',
                'availability' => 'unavailable',
                'current_latitude' => -6.1944,
                'current_longitude' => 106.8229,
                'last_location_update' => now(),
            ],
            [
                'fleet_number' => 'TRK002',
                'vehicle_type' => 'truck',
                'capacity' => 8.00,
                'driver_name' => 'Sarah Brown',
                'driver_phone' => '081234567894',
                'current_latitude' => -6.3026,
                'current_longitude' => 106.7337,
                'last_location_update' => now(),
            ],
            [
                'fleet_number' => 'VAN002',
                'vehicle_type' => 'van',
                'capacity' => 3.00,
                'driver_name' => 'David Lee',
                'driver_phone' => '081234567895',
                'availability' => 'maintenance',
            ],
        ];

        foreach ($fleets as $fleet) {
            Fleet::create($fleet);
        }
    }
}
