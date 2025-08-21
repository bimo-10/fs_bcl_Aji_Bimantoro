<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'fleet_id',
        'latitude',
        'longitude',
        'address',
        'notes',
        'checked_in_at'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'checked_in_at' => 'datetime'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }
}
