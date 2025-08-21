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
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('fleet_number')->unique();
            $table->enum('vehicle_type', ['truck', 'van', 'motorcycle', 'container']);
            $table->enum('availability', ['available', 'unavailable', 'maintenance'])->default('available');
            $table->decimal('capacity', 8, 2); // in tons
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('last_location_update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
