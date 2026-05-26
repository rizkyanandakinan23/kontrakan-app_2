<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Review;
use App\Models\Kamar;
use App\Models\ReviewReport;

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

        Review::create([
            'user_id' => Auth::id(),
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

        return back()->with('success', 'Review berhasil direport.');
    }
}