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
use Carbon\Carbon;

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
    // CEK USER SUDAH MULAI MENYEWA & LUNAS
    // =====================================
    $hasBooking = Booking::where('user_id', $userId)
        ->where('kamar_id', $kamar->id)
        ->whereDate('tanggal_masuk', '<=', Carbon::today())
        ->whereHas('payment', function ($q) {
            $q->where('status', 'success');
        })
        ->exists();

    if (!$hasBooking) {
        return back()->with(
            'error',
            'Anda hanya bisa memberikan review setelah masa sewa dimulai.'
        );
    }

    // =====================================
    // CEK REVIEW DUPLIKAT
    // =====================================
    $alreadyReview = Review::where('user_id', $userId)
        ->where('kamar_id', $kamar->id)
        ->exists();

    if ($alreadyReview) {
        return back()->with(
            'error',
            'Anda sudah memberikan review untuk kamar ini.'
        );
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

    return back()->with(
        'success',
        'Review berhasil dikirim.'
    );
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

        // $cek = ReviewReport::where('review_id', $review->id)
        //     ->where('user_id', Auth::id())
        //     ->first();

        // if ($cek) {

        //     return back()->with('error', 'Anda sudah mereport review ini.');
        // }

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

    // =====================================================
// NOTIF ADMIN - REVIEW DILAPORKAN
// =====================================================

$pelapor = Auth::user();

$admins = User::where('is_admin', 1)->get();

foreach ($admins as $admin) {

    $admin->notify(
        new SystemNotification(
            'Review Dilaporkan',
            $pelapor->nama_lengkap .
            ' melaporkan review milik ' .
            ($review->user->nama_lengkap ?? 'Pengguna') .
            ' pada kamar "' .
            ($review->kamar->nama_kamar ?? '-') .
            '". Alasan: ' .
            ($request->alasan ?? '-')
        )
    );

}

        return back()->with('success', 'Review berhasil direport.');
    }
}