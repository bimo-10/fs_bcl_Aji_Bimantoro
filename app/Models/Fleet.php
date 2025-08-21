<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $fillable = [
        'fleet_number',
        'vehicle_type',
        'availability',
        'capacity',
        'driver_name',
        'driver_phone',
        'current_latitude',
        'current_longitude',
        'last_location_update'
    ];

    protected $casts = [
        'capacity' => 'decimal:2',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'last_location_update' => 'datetime'
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function latestLocation()
    {
        return $this->hasOne(Location::class)->latestOfMany();
    }

    public function isAvailable()
    {
        return $this->availability === 'available';
    }

    public function getActiveShipmentsCountAttribute()
    {
        return $this->shipments()->where('status', 'in_transit')->count();
    }
}
