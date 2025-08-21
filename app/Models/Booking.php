<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'vehicle_type',
        'booking_date',
        'pickup_address',
        'delivery_address',
        'item_details',
        'weight',
        'customer_name',
        'customer_phone',
        'customer_email',
        'status',
        'fleet_id',
        'notes'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'weight' => 'decimal:2'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }

    /**
     * Generate unique booking number
     */
    public static function generateBookingNumber()
    {
        do {
            $bookingNumber = 'BKG' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('booking_number', $bookingNumber)->exists());

        return $bookingNumber;
    }

    public static function validateBookingDate($date)
    {
        return Carbon::parse($date)->isFuture() || Carbon::parse($date)->isToday();
    }

    public function assignFleet(Fleet $fleet)
    {
        $this->update([
            'fleet_id' => $fleet->id,
            'status' => 'assigned'
        ]);

        $fleet->update(['availability' => 'unavailable']);
    }

    public function complete()
    {
        $this->update(['status' => 'completed']);

        if ($this->fleet) {
            $this->fleet->update(['availability' => 'available']);
        }
    }
}
