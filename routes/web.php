<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('dashboard');
})->name('home');

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| KAMAR USER
|--------------------------------------------------------------------------
*/

Route::get('/kamar', [KamarController::class, 'index'])
    ->name('kamar.index');

Route::get('/kamar/{id}', [KamarController::class, 'show'])
    ->name('kamar.show');

/*
|--------------------------------------------------------------------------
| BOOKING
|--------------------------------------------------------------------------
*/

Route::get('/booking/{id}', [BookingController::class, 'index'])
    ->name('booking.index');

Route::post('/booking/{id}', [BookingController::class, 'store'])
    ->name('booking.store');

Route::post('/booking/{id}/upload-bukti', [BookingController::class, 'uploadBukti'])
    ->name('booking.uploadBukti');

Route::get('/riwayat-booking', [BookingController::class, 'riwayat'])
    ->name('booking.riwayat');

 /*
 |--------------------------------------------------------------------------
 | ADMIN PANEL
 |--------------------------------------------------------------------------
 */

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ADMIN
        |--------------------------------------------------------------------------
        */
        Route::get('/', [AdminController::class, 'index'])
            ->name('panel');

        Route::get('/user', [AdminController::class, 'userIndex'])
    ->name('user.index');

    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])
    ->name('user.delete');

        /*
        |--------------------------------------------------------------------------
        | KAMAR MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::get('/kamar', [AdminController::class, 'kamarIndex'])
            ->name('kamar.index');

        Route::get('/kamar/create', [AdminController::class, 'kamarCreate'])
            ->name('kamar.create');

        Route::post('/kamar/store', [AdminController::class, 'kamarStore'])
            ->name('kamar.store');

        Route::get('/kamar/{kamar}/edit', [AdminController::class, 'kamarEdit'])
            ->name('kamar.edit');

        Route::put('/kamar/{kamar}', [AdminController::class, 'kamarUpdate'])
            ->name('kamar.update');

        Route::delete('/kamar/{kamar}', [AdminController::class, 'kamarDestroy'])
            ->name('kamar.destroy');

        /*
        |--------------------------------------------------------------------------
        | BOOKING MANAGEMENT (FIXED)
        |--------------------------------------------------------------------------
        */
        Route::get('/booking', [AdminController::class, 'bookingIndex'])
            ->name('booking.index');

        Route::patch('/booking/{id}/approve', [AdminController::class, 'approveBooking'])
            ->name('booking.approve');

        Route::patch('/booking/{id}/reject', [AdminController::class, 'rejectBooking'])
            ->name('booking.reject');

        Route::delete('/booking/{id}', [AdminController::class, 'deleteBooking'])
            ->name('booking.delete');
    });

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/riwayat-booking', [BookingController::class, 'riwayat'])
        ->name('booking.riwayat');


});

/*
|--------------------------------------------------------------------------
| STATIC PAGES
|--------------------------------------------------------------------------
*/

Route::view('/qna', 'qna')
    ->name('qna');

Route::view('/ketentuan', 'ketentuan')
    ->name('ketentuan');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';