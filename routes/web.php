<?php

use App\Http\Controllers\AcademicDateController;
use App\Http\Controllers\BlackoutController;
use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\BookingReviewController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RestrictionsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomDateRestrictionsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocialLoginController;
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

Route::get('login/microsoft', [LoginController::class, 'redirectToProvider'])->name('login/microsoft');
Route::get('login/microsoft/callback', [LoginController::class, 'handleProviderCallback']);

Route::get('login/microsoft', [SocialLoginController::class, 'redirectToProvider'])->name('login/microsoft');
Route::get('login/microsoft/callback', [SocialLoginController::class, 'handleProviderCallback'])->name('login/microsoft/callback');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');

    /**
     * ADMIN
     */
    Route::name('admin.')->prefix('admin')->group(function () {
        //TODO: Admin index route

        /**
         * USERS
         */
        Route::name('users.')->prefix('users')->group(function() {
            Route::get('/', [UserController::class, 'index'])
                ->name('index');

            Route::post('/', [UserController::class, 'store'])
                ->name('store')
                ->middleware(['permission:users.create']);

            Route::put('/{user}', [UserController::class, 'update'])
                ->name('update')
                ->middleware(['permission:users.update']);

            Route::delete('/{user}', [UserController::class, 'destroy'])
                ->name('destroy')
                ->middleware(['permission:users.delete']);
        });

        /**
         * ROLES
         */
        Route::name('roles.')->prefix('roles')->group(function() {
            Route::get('/', [RoleController::class, 'index'])
                ->name('index');

            Route::post('/', [RoleController::class, 'store'])
                ->name('store')
                ->middleware(['permission:roles.create']);

            Route::put('/{role}', [RoleController::class, 'update'])
                ->name('update')
                ->middleware(['permission:roles.update']);

            Route::delete('/{role}', [RoleController::class, 'destroy'])
                ->name('destroy')
                ->middleware(['permission:roles.delete']);
        });

        /**
         * ROOMS
         */
        Route::name('rooms.')->prefix('rooms')->group(function() {
            Route::get('/', [RoomController::class, 'index'])
                ->name('index');

            Route::post('/', [RoomController::class, 'store'])
                ->name('store')
                ->middleware(['permission:rooms.create']);

            Route::put('/{room}', [RoomController::class, 'update'])
                ->name('update')
                ->middleware(['permission:rooms.update']);

            Route::delete('/{room}', [RoomController::class, 'destroy'])
                ->name('destroy')
                ->middleware(['permission:rooms.delete']);

            Route::put('/{room}/restrictions', [RestrictionsController::class, 'update'])
                ->name('restrictions.update')
                ->middleware('permission:bookings.approve');

            Route::put('/{room}/date-restrictions', RoomDateRestrictionsController::class)
                ->name('date.restrictions.update')
                ->middleware('permission:bookings.approve');

            Route::post('/blackouts/all', [BlackoutController::class,  'createBlackoutForEveryRoom'])
                ->name('all_blackout')
                ->middleware(['permission:rooms.blackouts.create']);

            Route::name('blackouts.')->prefix('{room}/blackouts')->group(function() {
                Route::get('/', [BlackoutController::class, 'index'])
                    ->name('index');

                Route::post('/', [BlackoutController::class, 'store'])
                    ->name('store')
                    ->middleware(['permission:rooms.blackouts.create']);

                Route::put('/{blackout}', [BlackoutController::class, 'update'])
                    ->name('update')
                    ->middleware(['permission:rooms.blackouts.update']);

                Route::delete('/{blackout}', [BlackoutController::class, 'destroy'])
                    ->name('destroy')
                    ->middleware(['permission:rooms.blackouts.delete']);
            });
        });

        /**
         * SETTINGS
         */
        Route::name('settings.')->prefix('settings')->middleware(['permission:settings.edit'])->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::post('app_logo', [SettingsController::class, 'storeAppLogo'])->name('app.logo');
            Route::post('app_name', [SettingsController::class, 'storeAppName'])->name('app.name');
            Route::post('app_config', [SettingsController::class, 'setAppConfig'])->name('app.config');
            Route::post('/academic_date/{academicDate}', [AcademicDateController::class, 'updateAcademicDate'])->name('app.academic_date');
        });
    });

    /**
     * BOOKINGS
     */
    Route::prefix('bookings')->name('bookings.')->group(function () {
        /**
         * ROLES
         */
        Route::get('/', [BookingRequestController::class, 'list'])
            ->name('index')
            ->middleware(['permission:bookings.create']);

        Route::get('/create', [BookingRequestController::class, 'create'])
            ->name('create')
            ->middleware(['permission:bookings.create']);

        Route::post('/create', [BookingRequestController::class, 'createInit'])
            ->name('createInit')
            ->middleware(['permission:bookings.create']);

        Route::post('/', [BookingRequestController::class, 'store'])
            ->name('store')
            ->middleware(['permission:bookings.create']);

        Route::get('/{booking}/edit', [BookingRequestController::class, 'edit'])
            ->name('edit')
            ->middleware(['permission:bookings.update']);

        Route::get('{booking}/view', [BookingRequestController::class, 'show'])->name('view');

        Route::put('/{booking}', [BookingRequestController::class, 'update'])
            ->name('update')
            ->middleware(['permission:bookings.update']);

        Route::delete('/{booking}', [BookingRequestController::class, 'destroy'])
            ->name('destroy')
            ->middleware(['permission:bookings.delete']);

        Route::get('/search', [BookingRequestController::class, 'index'])
            ->name('search')
            ->middleware(['permission:bookings.create']);

        Route::get('download/{folder}', [BookingRequestController::class, 'downloadReferenceFiles'])->name('download');

        Route::name('reviews.')->middleware('permission:bookings.approve')->group(function () {
            Route::get('review', [BookingReviewController::class, 'index'])->name('index');
            Route::get('{booking}/review', [BookingReviewController::class, 'show'])->name('show');
            Route::post('{booking}/review', [BookingReviewController::class, 'review'])->name('update');
            Route::post('{booking}/assign', [BookingReviewController::class, 'assign'])->name('assign');
        });
    });

    Route::resource('reservation', ReservationsController::class)->middleware(['permission:bookings.create']);

});
