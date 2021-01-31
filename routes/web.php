<?php

use App\Http\Controllers\BlackoutController;
use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RestrictionsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return redirect()->route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
  Route::get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
  })->name('dashboard');

  Route::name('admin.')->prefix('admin')->group(function () {
    //TODO: Admin index route
    Route::resource('users', UserController::class)->only(['store', 'index', 'destroy', 'update']);
    Route::resource('roles', RoleController::class)->except(['create', 'show', 'edit']);

    Route::resource('rooms', RoomController::class)->only(['store', 'index', 'update', 'destroy']);
    Route::put('rooms/{room}/restrictions', [RestrictionsController::class, 'update'])
      ->name('room.restrictions.update')->middleware('permission:bookings.approve');
    Route::resource('rooms.blackouts', BlackoutController::class)->only(['index', 'store', 'destroy', 'update']);

    Route::name('settings.')->prefix('settings')->group(function () {
      Route::get('/', [SettingsController::class, 'index'])->name('index');
      Route::post('app_logo', [SettingsController::class, 'storeAppLogo'])->name('app.logo');
      Route::post('app_name', [SettingsController::class, 'storeAppName'])->name('app.name');
    });
  });

  Route::prefix('bookings')->name('bookings.')->group(function () {
    Route::resource('', BookingRequestController::class)->except('show')->parameters(['' => 'booking']);
    Route::post('create', [BookingRequestController::class, 'createInit'])->name('createInit');
    Route::get('list', [BookingRequestController::class, 'list'])->name('list');
    Route::get('download/{folder}', [BookingRequestController::class, 'downloadReferenceFiles'])->name('download');
  });

  Route::resource('reservation', ReservationsController::class);

});
