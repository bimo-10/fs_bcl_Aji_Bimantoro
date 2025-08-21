<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Fleet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fleets = Fleet::all();

        $bookings = [
            [
                'booking_number' => 'BKG20250821001',
                'vehicle_type' => 'truck',
                'booking_date' => now()->addDays(1),
                'pickup_address' => 'Warehouse A, Jakarta Utara',
                'delivery_address' => 'Distribution Center, Bogor',
                'item_details' => 'Bulk electronics shipment for retail stores',
                'weight' => 8.5,
                'customer_name' => 'PT Retail Electronics',
                'customer_phone' => '02187654321',
                'customer_email' => 'logistics@retailelectronics.com',
                'status' => 'assigned',
                'fleet_id' => $fleets->where('fleet_number', 'TRK002')->first()?->id,
            ],
            [
                'booking_number' => 'BKG20250821002',
                'vehicle_type' => 'van',
                'booking_date' => now()->addDays(2),
                'pickup_address' => 'Home - Jl. Sudirman No. 123, Jakarta',
                'delivery_address' => 'New Office - Jl. Gatot Subroto No. 456, Jakarta',
                'item_details' => 'Office furniture and equipment for relocation',
                'weight' => 2.3,
                'customer_name' => 'Rina Sari',
                'customer_phone' => '08199887766',
                'customer_email' => 'rina.sari@email.com',
                'status' => 'confirmed',
            ],
            [
                'booking_number' => 'BKG20250821003',
                'vehicle_type' => 'motorcycle',
                'booking_date' => now()->addDays(1),
                'pickup_address' => 'Restaurant Central, Kemang',
                'delivery_address' => 'Various locations in South Jakarta',
                'item_details' => 'Food delivery - Multiple orders',
                'weight' => 0.8,
                'customer_name' => 'Food Delivery Service',
                'customer_phone' => '08155443322',
                'customer_email' => 'dispatch@fooddelivery.com',
                'status' => 'pending',
            ],
            [
                'booking_number' => 'BKG20250821004',
                'vehicle_type' => 'container',
                'booking_date' => now()->addDays(3),
                'pickup_address' => 'Port of Tanjung Priok, Jakarta',
                'delivery_address' => 'Industrial Area, Karawang',
                'item_details' => 'Import goods - Manufacturing raw materials',
                'weight' => 22.0,
                'customer_name' => 'PT Manufacturing Industries',
                'customer_phone' => '02112332211',
                'customer_email' => 'import@manufacturing.co.id',
                'status' => 'pending',
                'notes' => 'Requires special handling and documentation',
            ],
            [
                'booking_number' => 'BKG20250821005',
                'vehicle_type' => 'van',
                'booking_date' => now()->addDays(1),
                'pickup_address' => 'Event Venue, Senayan',
                'delivery_address' => 'Storage Facility, Cibubur',
                'item_details' => 'Event equipment - Sound system, lighting, decorations',
                'weight' => 4.2,
                'customer_name' => 'Event Organizer Pro',
                'customer_phone' => '08177889900',
                'customer_email' => 'ops@eventpro.com',
                'status' => 'confirmed',
            ],
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
