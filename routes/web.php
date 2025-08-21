<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ReportController::class, 'index'])->name('dashboard');

Route::resource('fleets', FleetController::class);
Route::put('fleets/{fleet}/location', [FleetController::class, 'updateLocation'])->name('fleets.update-location');

Route::resource('shipments', ShipmentController::class);

Route::resource('bookings', BookingController::class);
Route::post('bookings/{booking}/assign-fleet', [BookingController::class, 'assignFleet'])->name('bookings.assign-fleet');

Route::get('tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('tracking', [TrackingController::class, 'track'])->name('tracking.track');

Route::get('locations', [LocationController::class, 'index'])->name('locations.index');
Route::post('fleets/{fleet}/checkin', [LocationController::class, 'checkin'])->name('locations.checkin');
Route::get('fleets/{fleet}/locations', [LocationController::class, 'history'])->name('locations.history');

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('fleet-performance', [ReportController::class, 'fleetPerformance'])->name('fleet-performance');
    Route::get('shipment-status', [ReportController::class, 'shipmentStatus'])->name('shipment-status');
    Route::get('revenue', [ReportController::class, 'revenue'])->name('revenue');
    Route::get('export/shipments-in-transit', [ReportController::class, 'exportShipmentsInTransit'])->name('export.shipments-in-transit');
});

Route::prefix('api')->name('api.')->group(function () {
    Route::post('tracking', [TrackingController::class, 'apiTrack'])->name('tracking');
    Route::get('fleet-locations', [LocationController::class, 'apiFleetLocations'])->name('fleet-locations');
    Route::get('fleets/{fleet}/location', [LocationController::class, 'apiFleetLocation'])->name('fleet-location');
});
