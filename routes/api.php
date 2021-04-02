<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\BookingReviewController;
use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\SettingsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/filterBookingRequests', [BookingRequestController::class, 'filter'])->middleware(['permission:bookings.approve']);
    Route::post('/filterMyBookingRequests', [BookingRequestController::class, 'filterUserBookings'])->middleware(['permission:bookings.create']);
    Route::post('/filterRooms', [RoomController::class, 'filter'])->middleware(['permission:bookings.create']);

    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::name('by-date')->get('by-date', [ReservationsController::class, 'byDate']);
        Route::name('by-room')->get('by-room/{room}', [ReservationsController::class, 'byRoom']);
    });

    Route::patch('/booking-setting', [SettingsController::class, 'storeBookingGeneralInformation']);

    Route::name('bookings.reviews.assignable')->middleware(['permission:bookings.approve'])
        ->get('/bookings/assignable', [BookingReviewController::class, 'assignable']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
