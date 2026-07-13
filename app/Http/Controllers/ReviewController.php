<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SystemNotification;

use App\Models\Review;
use App\Models\Kamar;
use App\Models\ReviewReport;
use App\Models\User;
use App\Models\Booking;

class ReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STORE REVIEW
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $id)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'required'
    ]);

    $kamar = Kamar::findOrFail($id);

    $userId = Auth::id();

    // =====================================
    // CEK USER SUDAH BOOKING & LUNAS
    // =====================================
    $hasBooking = Booking::where('user_id', $userId)
    ->where('kamar_id', $kamar->id)
    ->whereHas('payment', function ($q) {
        $q->where('status', 'success');
    })
    ->exists();

    if (!$hasBooking) {
        return back()->with('error', 'Anda hanya bisa memberi review setelah menyewa kamar ini.');
    }

    // =====================================
    // CEK REVIEW DUPLIKAT (OPTIONAL TAPI DISARANKAN)
    // =====================================
    $alreadyReview = Review::where('user_id', $userId)
        ->where('kamar_id', $kamar->id)
        ->exists();

    if ($alreadyReview) {
        return back()->with('error', 'Anda sudah memberikan review untuk kamar ini.');
    }

    // =====================================
    // SIMPAN REVIEW
    // =====================================
    Review::create([
        'user_id' => $userId,
        'kamar_id' => $kamar->id,
        'rating' => $request->rating,
        'komentar' => $request->komentar
    ]);

    return back()->with('success', 'Review berhasil dikirim.');
}

    /*
    |--------------------------------------------------------------------------
    | DELETE REVIEW
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PEMILIK REVIEW
        |--------------------------------------------------------------------------
        */

        if ($review->user_id != Auth::id()) {

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS REPORT TERKAIT
        |--------------------------------------------------------------------------
        */

        ReviewReport::where('review_id', $review->id)->delete();

        /*
        |--------------------------------------------------------------------------
        | HAPUS REVIEW
        |--------------------------------------------------------------------------
        */

        $review->delete();

        return back()->with('success', 'Review berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | REPORT REVIEW
    |--------------------------------------------------------------------------
    */

    public function report(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | CEGAH REPORT REVIEW SENDIRI
        |--------------------------------------------------------------------------
        */

        if ($review->user_id == Auth::id()) {

            return back()->with('error', 'Review sendiri tidak bisa direport.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK SUDAH REPORT ATAU BELUM
        |--------------------------------------------------------------------------
        */

        $cek = ReviewReport::where('review_id', $review->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($cek) {

            return back()->with('error', 'Anda sudah mereport review ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN REPORT
        |--------------------------------------------------------------------------
        */

        ReviewReport::create([
            'review_id' => $review->id,
            'user_id' => Auth::id(),
            'alasan' => $request->alasan
        ]);

        /*
    |----------------------------------------
    | 🔥 KIRIM NOTIF KE ADMIN
    |----------------------------------------
    */
    User::where('is_admin', true)->get()
        ->each(function ($admin) use ($review, $request) {
            $admin->notify(new SystemNotification(
                'Review Dilaporkan',
                'Review dari user ID ' . $review->user_id .
                ' dilaporkan dengan alasan: ' . $request->alasan,
                'review-report'
            ));
        });

        return back()->with('success', 'Review berhasil direport.');
    }
}