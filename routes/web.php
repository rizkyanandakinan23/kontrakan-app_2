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

/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ADMIN
        |--------------------------------------------------------------------------
        */

        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.panel');

        /*
        |--------------------------------------------------------------------------
        | CRUD KAMAR
        |--------------------------------------------------------------------------
        */

        Route::get('/kamar', [AdminController::class, 'kamarIndex'])
            ->name('admin.kamar.index');

        Route::get('/kamar/create', [AdminController::class, 'kamarCreate'])
            ->name('admin.kamar.create');

        Route::post('/kamar/store', [AdminController::class, 'kamarStore'])
            ->name('admin.kamar.store');

        Route::get('/kamar/{kamar}/edit', [AdminController::class, 'kamarEdit'])
            ->name('admin.kamar.edit');

        Route::put('/kamar/{kamar}', [AdminController::class, 'kamarUpdate'])
            ->name('admin.kamar.update');

        Route::delete('/kamar/{kamar}', [AdminController::class, 'kamarDestroy'])
            ->name('admin.kamar.destroy');

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