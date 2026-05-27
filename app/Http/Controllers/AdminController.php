<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\User;
use App\Models\Booking;
use App\Models\Review;
use App\Models\ReviewReport;
use App\Models\Conversation;
use App\Models\Message;
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
    $booking = Booking::with(['user', 'kamar'])->findOrFail($id);

    // UPDATE STATUS BOOKING
    $booking->update([
        'status_pembayaran' => 'dibayar'
    ]);

    // UPDATE STATUS KAMAR
    if ($booking->kamar) {

        $booking->kamar->update([
            'status' => 'terisi'
        ]);

    }

    // FORMAT NOMOR
    $nomor = preg_replace('/[^0-9]/', '', $booking->whatsapp);

    if (substr($nomor, 0, 1) == '0') {
        $nomor = '62' . substr($nomor, 1);
    }

    // PESAN WA
    $pesan = urlencode(
        "Halo {$booking->user->nama_lengkap}, "
        . "booking kamar '{$booking->kamar->nama_kamar}' "
        . "telah DISETUJUI dan pembayaran berhasil diverifikasi. "
        . "Silakan datang ke kontrakan sesuai jadwal. Terima kasih."
    );

    $waLink = "https://wa.me/{$nomor}?text={$pesan}";

    return redirect()
        ->back()
        ->with('success', 'Booking berhasil diapprove')
        ->with('wa_link', $waLink);
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

/*
|--------------------------------------------------------------------------
| CHAT MANAGEMENT
|--------------------------------------------------------------------------
*/

public function chatIndex()
{
    $conversations = Conversation::with([
            'user',
            'messages'
        ])
        ->latest()
        ->get();

    return view('admin.chat.whatsapp', compact('conversations'));
}

public function chatOpen(Conversation $conversation)
{
    $messages = $conversation->messages()
        ->with('sender')
        ->orderBy('created_at')
        ->get();

    return response()->json([

        'user' => [
            'nama' => $conversation->user->nama_lengkap
        ],

        'messages' => $messages->map(function($msg){

            return [

                'sender_id' => $msg->sender_id,

                'message' => $msg->message,

                'image' => $msg->image,

                'time' => $msg->created_at->format('H:i'),

                'is_admin' =>
                    $msg->sender_id == auth()->id()

            ];

        })

    ]);
}

public function chatSend(Request $request, Conversation $conversation)
{
    $request->validate([
        'message' => 'nullable|string',
        'image' => 'nullable|image|max:2048'
    ]);

    if (!$request->message && !$request->hasFile('image')) {

        return response()->json([
            'status' => false,
            'message' => 'Pesan kosong'
        ]);

    }

    $imagePath = null;

    if ($request->hasFile('image')) {

        $imagePath = $request->file('image')
            ->store('chat', 'public');

    }

    $message = Message::create([

        'conversation_id' => $conversation->id,

        'sender_id' => auth()->id(),

        'message' => $request->message ?? '',

        'image' => $imagePath,

    ]);

    return response()->json([

        'status' => true,

        'data' => [

            'message' => $message->message,

            'image' => $message->image,

            'created_at' => $message->created_at->format('H:i')

        ]

    ]);
}

}