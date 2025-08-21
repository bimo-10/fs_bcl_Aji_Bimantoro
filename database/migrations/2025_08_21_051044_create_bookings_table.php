<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->enum('vehicle_type', ['truck', 'van', 'motorcycle', 'container']);
            $table->date('booking_date');
            $table->string('pickup_address');
            $table->string('delivery_address');
            $table->text('item_details');
            $table->decimal('weight', 8, 2); // in kg
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'assigned', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('fleet_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
