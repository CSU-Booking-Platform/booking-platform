<?php

use App\Http\Controllers\RestrictionsController;
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

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/dashboard', function () {
        return Inertia\Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class)->only(['store', 'index', 'destroy', 'update']);

    Route::resource('roles',\App\Http\Controllers\RoleController::class)->except(['show', 'edit']);
    Route::resource('rooms',\App\Http\Controllers\RoomController::class)->only(['store', 'index', 'update', 'destroy']);
    Route::put('room/restrictions/{id}', RestrictionsController::class.'@update')
        ->name('room.restrictions.update')->middleware('permission:bookings.approve');

    Route::resource('settings',SettingsController::class)->only(['index']);
    Route::post('settings/app_logo', SettingsController::class.'@storeAppLogo')->name('app.logo.change');
    Route::post('settings/app_name', SettingsController::class.'@storeAppName')->name('app.name.change');

    Route::resource('bookings',\App\Http\Controllers\BookingRequestController::class)->only(['store', 'index', 'update', 'destroy']);

    if (env('APP_ENV') == 'local' || env('APP_ENV') == 'testing') {
        Route::resource('demo/tables',\App\Http\Controllers\DemoController::class)->only(['index']);
    }
});
