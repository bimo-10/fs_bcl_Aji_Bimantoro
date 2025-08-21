<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\Fleet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fleets = Fleet::all();

        $shipments = [
            [
                'tracking_number' => 'TRK20250821001',
                'shipment_date' => now()->subDays(2),
                'origin_address' => 'Jakarta Pusat, DKI Jakarta',
                'destination_address' => 'Bandung, Jawa Barat',
                'status' => 'in_transit',
                'item_details' => 'Electronics - 2x Laptop, 1x Monitor',
                'weight' => 5.5,
                'value' => 25000000,
                'recipient_name' => 'Ahmad Susanto',
                'recipient_phone' => '08123456789',
                'sender_name' => 'PT Technology Solutions',
                'sender_phone' => '02112345678',
                'fleet_id' => $fleets->where('fleet_number', 'TRK001')->first()?->id,
            ],
            [
                'tracking_number' => 'TRK20250821002',
                'shipment_date' => now()->subDays(1),
                'origin_address' => 'Surabaya, Jawa Timur',
                'destination_address' => 'Malang, Jawa Timur',
                'status' => 'in_transit',
                'item_details' => 'Furniture - 1x Office Desk, 2x Chairs',
                'weight' => 45.0,
                'value' => 3500000,
                'recipient_name' => 'Siti Rahayu',
                'recipient_phone' => '08987654321',
                'sender_name' => 'CV Furniture Jaya',
                'sender_phone' => '03112345678',
                'fleet_id' => $fleets->where('fleet_number', 'CON001')->first()?->id,
            ],
            [
                'tracking_number' => 'TRK20250821003',
                'shipment_date' => now(),
                'origin_address' => 'Bekasi, Jawa Barat',
                'destination_address' => 'Depok, Jawa Barat',
                'status' => 'pending',
                'item_details' => 'Documents - Important contracts and certificates',
                'weight' => 0.2,
                'value' => 0,
                'recipient_name' => 'Budi Santoso',
                'recipient_phone' => '08111222333',
                'sender_name' => 'PT Legal Services',
                'sender_phone' => '02198765432',
            ],
            [
                'tracking_number' => 'TRK20250821004',
                'shipment_date' => now()->subDays(5),
                'origin_address' => 'Yogyakarta, DI Yogyakarta',
                'destination_address' => 'Solo, Jawa Tengah',
                'status' => 'delivered',
                'item_details' => 'Handicrafts - Traditional batik and silverware',
                'weight' => 3.2,
                'value' => 1500000,
                'recipient_name' => 'Indah Permatasari',
                'recipient_phone' => '08567891234',
                'sender_name' => 'Toko Souvenir Jogja',
                'sender_phone' => '02743218765',
                'fleet_id' => $fleets->where('fleet_number', 'VAN001')->first()?->id,
                'delivered_at' => now()->subDays(3),
            ],
            [
                'tracking_number' => 'TRK20250821005',
                'shipment_date' => now(),
                'origin_address' => 'Tangerang, Banten',
                'destination_address' => 'Jakarta Selatan, DKI Jakarta',
                'status' => 'in_transit',
                'item_details' => 'Food packages - Fresh produce and snacks',
                'weight' => 1.8,
                'value' => 350000,
                'recipient_name' => 'Lisa Wijaya',
                'recipient_phone' => '08333444555',
                'sender_name' => 'Fresh Market Tangerang',
                'sender_phone' => '02154321987',
                'fleet_id' => $fleets->where('fleet_number', 'MTR001')->first()?->id,
            ],
        ];

        foreach ($shipments as $shipment) {
            Shipment::create($shipment);
        }
    }
}
