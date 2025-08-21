<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'shipment_date',
        'origin_address',
        'origin_latitude',
        'origin_longitude',
        'destination_address',
        'destination_latitude',
        'destination_longitude',
        'status',
        'item_details',
        'weight',
        'value',
        'recipient_name',
        'recipient_phone',
        'sender_name',
        'sender_phone',
        'fleet_id',
        'delivered_at'
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'origin_latitude' => 'decimal:8',
        'origin_longitude' => 'decimal:8',
        'destination_latitude' => 'decimal:8',
        'destination_longitude' => 'decimal:8',
        'weight' => 'decimal:2',
        'value' => 'decimal:2',
        'delivered_at' => 'datetime'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }

    public static function generateTrackingNumber()
    {
        do {
            $trackingNumber = 'TRK' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    public function isInTransit()
    {
        return $this->status === 'in_transit';
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);
    }
}
