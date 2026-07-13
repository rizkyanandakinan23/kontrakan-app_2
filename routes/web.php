<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Models\Kamar;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])
    ->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');
/*
|--------------------------------------------------------------------------
| KAMAR USER
|--------------------------------------------------------------------------
*/

Route::controller(KamarController::class)->group(function () {

    Route::get('/kamar', 'index')
        ->name('kamar.index');

    Route::get('/kamar/{id}', 'show')
        ->name('kamar.show');

});
/*
|--------------------------------------------------------------------------
| CHAT SYSTEM
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

});

/*
|--------------------------------------------------------------------------
| REVIEW
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/review/{id}', [ReviewController::class, 'store'])
        ->name('review.store');

    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])
        ->name('review.destroy'); // USER DELETE OWN REVIEW

    Route::patch('/review/{id}/report', [ReviewController::class, 'report'])
        ->name('review.report');

});


/*
|--------------------------------------------------------------------------
| BOOKING
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function(){

    Route::get('/booking/{id}',
        [BookingController::class,'index']
    )->name('booking.index');


    Route::post('/booking/{id}',
        [BookingController::class,'store']
    )->name('booking.store');


    Route::get('/riwayat-booking',
        [BookingController::class,'riwayat']
    )->name('booking.riwayat');


    Route::patch('/booking/{id}/cancel',
        [BookingController::class,'cancel']
    )->name('booking.cancel');


});

/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function(){


    Route::get('/payment/{id}',
        [PaymentController::class,'create']
    )->name('payment.create');


    Route::post('/payment/callback',
        [PaymentController::class,'callback']
    )->name('payment.callback');


    Route::get('/payment/success/{id}',
        [PaymentController::class,'success']
    )->name('payment.success');

});

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

/*
        |--------------------------------------------------------------------------
        | Notification Admin
        |--------------------------------------------------------------------------
        */
            Route::get('/notifications', [AdminController::class, 'notificationIndex'])
    ->name('notifications');
        /*
        |--------------------------------------------------------------------------
        | USER MANAGEMENT
        |--------------------------------------------------------------------------
        */

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

        Route::post('/kamar/set-semua-kosong', [AdminController::class, 'setSemuaKosong'])
    ->name('kamar.setSemuaKosong');

        /*
        |--------------------------------------------------------------------------
        | BOOKING MANAGEMENT
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

            Route::get('/booking/{id}', [AdminController::class, 'bookingDetail'])
    ->name('booking.detail');
  
/*
|--------------------------------------------------------------------------
| PAYMENT MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::get('/pembayaran', [AdminController::class, 'pembayaranIndex'])
    ->name('pembayaran.index');

        /*
        |--------------------------------------------------------------------------
        | REVIEW MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get('/review', [AdminController::class, 'reviewIndex'])
            ->name('review.index');

        Route::delete('/review/{id}', [AdminController::class, 'reviewDelete'])
            ->name('review.delete');

        Route::patch('/review/{id}/ignore', [AdminController::class, 'reviewIgnore'])
    ->name('review.ignore');

        /*
|--------------------------------------------------------------------------
| CHAT MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::get('/chat', [AdminController::class, 'chatIndex'])
    ->name('chat.index');

Route::get('/chat/open/{conversation}', [AdminController::class, 'chatOpen'])
    ->name('chat.open');

Route::post('/chat/send/{conversation}', [AdminController::class, 'chatSend'])
    ->name('chat.send');

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
| NOTIFICATION
|--------------------------------------------------------------------------
*/

// USER
Route::get('/user/notifications', [NotificationController::class, 'index'])
    ->name('notifications.user')
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::post(
    '/midtrans/callback',
    [PaymentController::class, 'callback']
)->name('midtrans.callback');

Route::get('/fake-success/{id}',
[PaymentController::class,'success']);


require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| TEST NOTIFICATION
|--------------------------------------------------------------------------
*/
Route::get('/test-notif', function () {

    $user = User::first();

    $user->notify(
        new SystemNotification(
            'Test Notifikasi',
            'Notifikasi berhasil dibuat.'
        )
    );

    return 'Notifikasi berhasil dikirim';
});

