<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|laravel@127.0.0.1
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('users', UserController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/dashboard', function () {
        return Inertia\Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('roles',\App\Http\Controllers\RoleController::class);
});
