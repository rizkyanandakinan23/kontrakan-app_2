<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\User;
use App\Models\Booking;
use App\Models\Review;
use App\Models\ReviewReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $totalUser = User::count();
        $totalKamar = Kamar::count();
        $kamarTerisi = Kamar::where('status', 'terisi')->count();

        $users = User::latest()->take(5)->get();

        return view('admin.adminpanel', compact(
            'totalUser',
            'totalKamar',
            'kamarTerisi',
            'users'
        ));
    }

    public function userIndex()
{
    $users = User::latest()->get();

    return view('admin.user.adminuser', compact('users'));
}

public function deleteUser($id)
{
    $user = User::findOrFail($id);

    // ❌ blok admin
    if ($user->is_admin == 1) {
        return back()->with('error', 'Akun admin tidak dapat dihapus.');
    }

    $user->delete();

    return back()->with('success', 'Akun user berhasil dihapus');
}

    /*
    |--------------------------------------------------------------------------
    | ================= BOOKING MANAGEMENT =================
    |--------------------------------------------------------------------------
    */

    // LIST BOOKING
    public function bookingIndex()
    {
        $bookings = Booking::with(['user', 'kamar'])
            ->latest()
            ->get();

        return view('admin.booking.adminbooking', compact('bookings'));
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE BOOKING
    |--------------------------------------------------------------------------
    */
   public function approveBooking($id)
{
    $booking = Booking::findOrFail($id);

    // update status pembayaran
    $booking->update([
        'status_pembayaran' => 'dibayar'
    ]);

    // update status kamar
    $kamar = Kamar::find($booking->kamar_id);

    if ($kamar) {
        $kamar->update([
            'status' => 'terisi'
        ]);
    }

    return back()->with('success', 'Pembayaran disetujui');
}

public function rejectBooking($id)
{
    $booking = Booking::findOrFail($id);

    $booking->update([
        'status_pembayaran' => 'ditolak'
    ]);

    return back()->with('success', 'Pembayaran ditolak');
}

public function deleteBooking($id)
{
    $booking = Booking::findOrFail($id);

    if ($booking->bukti_pembayaran) {
        \Storage::disk('public')->delete($booking->bukti_pembayaran);
    }

    $booking->delete();

    return back()->with('success', 'Booking dihapus');
}

    /*
    |--------------------------------------------------------------------------
    | ================= KAMAR MANAGEMENT =================
    |--------------------------------------------------------------------------
    */

    public function kamarIndex()
    {
        $kamars = Kamar::latest()->get();

        return view('admin.kamar.index', compact('kamars'));
    }

    public function kamarCreate()
    {
        return view('admin.kamar.create');
    }

    public function kamarStore(Request $request)
    {
        $request->validate([
            'nama_kamar' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric',
            'fasilitas' => 'nullable|array',
            'foto_kamar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPaths = [];

        if ($request->hasFile('foto_kamar')) {
            foreach ($request->file('foto_kamar') as $foto) {
                $fotoPaths[] = $foto->store('kamar', 'public');
            }
        }

        Kamar::create([
            'nama_kamar' => $request->nama_kamar,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'status' => 'kosong',
            'fasilitas' => $request->fasilitas ?? [],
            'foto_kamar' => $fotoPaths,
        ]);

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil ditambahkan');
    }

    public function kamarEdit(Kamar $kamar)
    {
        return view('admin.kamar.edit', compact('kamar'));
    }

    public function kamarUpdate(Request $request, Kamar $kamar)
    {
        $request->validate([
            'nama_kamar' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric',
            'fasilitas' => 'nullable|array',
            'foto_kamar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoLama = $kamar->foto_kamar ?? [];

        if (!is_array($fotoLama)) {
            $fotoLama = [];
        }

        $fotoBaru = $fotoLama;

        if ($request->hasFile('foto_kamar')) {

            foreach ($fotoLama as $foto) {
                Storage::disk('public')->delete($foto);
            }

            $fotoBaru = [];

            foreach ($request->file('foto_kamar') as $foto) {
                $fotoBaru[] = $foto->store('kamar', 'public');
            }
        }

        $kamar->update([
            'nama_kamar' => $request->nama_kamar,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'status' => $request->status ?? $kamar->status,
            'fasilitas' => $request->fasilitas ?? [],
            'foto_kamar' => $fotoBaru,
        ]);

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil diupdate');
    }

    public function kamarDestroy(Kamar $kamar)
    {
        $fotos = $kamar->foto_kamar ?? [];

        if (is_array($fotos)) {
            foreach ($fotos as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }

        $kamar->delete();

        return back()->with('success', 'Kamar berhasil dihapus');
    }

    /*
|--------------------------------------------------------------------------
| ================= REVIEW MANAGEMENT =================
|--------------------------------------------------------------------------
*/

public function reviewIndex()
{
    $reviews = Review::with([
        'user',
        'kamar'
    ])
    ->withCount('reports')
    ->latest()
    ->get();

    return view('admin.review.adminreview', compact('reviews'));
}

public function reviewDelete($id)
{
    $review = Review::findOrFail($id);

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

    return back()->with('success', 'Review berhasil dihapus');
}
}