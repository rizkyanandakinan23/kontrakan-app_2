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
}