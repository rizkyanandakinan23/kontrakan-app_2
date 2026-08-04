<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ambil semua notif terbaru
        $notifications = $user->notifications()
            ->latest()
            ->get();

        // // 🔥 hanya notif external
        // $notifications = $user->notifications()
        //     ->where('data->type', 'external')
        //     ->latest()
        //     ->get();

        // tandai sebagai dibaca (hanya external juga)
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

        public function destroy($id)
    {
        auth()->user()
            ->notifications()
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function adminIndex()
    {
        $admin = auth()->user();

        $notifications = $admin->notifications()
            ->latest()
            ->get();

        // Tandai semua sebagai sudah dibaca
        $admin->unreadNotifications()
            ->update(['read_at' => now()]);

        return view('admin.notifications.index', compact('notifications'));
    }

        public function adminDestroy($id)
    {
        auth()->user()
            ->notifications()
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

}