<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $admin = User::where('is_admin', 1)->first();

        if (!$admin) {
            abort(500, 'Admin belum tersedia');
        }

        $conversation = Conversation::firstOrCreate([
            'user_id' => $user->id,
            'admin_id' => $admin->id,
        ]);

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.index', compact('messages', 'conversation'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        $admin = User::where('is_admin', 1)->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin tidak ditemukan'
            ], 400);
        }

        $conversation = Conversation::firstOrCreate([
            'user_id' => $user->id,
            'admin_id' => $admin->id,
        ]);

        // =========================
        // HANDLE IMAGE UPLOAD
        // =========================
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat', 'public');
        }

        // =========================
        // VALIDASI: HARUS ADA SALAH SATU
        // =========================
        if (!$request->message && !$imagePath) {
            return response()->json([
                'status' => false,
                'message' => 'Pesan atau gambar harus diisi'
            ], 422);
        }

        // =========================
        // CREATE MESSAGE
        // =========================
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message ?? '',
            'image' => $imagePath,
        ]);

        // =========================
        // RESPONSE JSON (FRONTEND FRIENDLY)
        // =========================
        return response()->json([
            'status' => true,
            'data' => [
                'id' => $message->id,
                'message' => $message->message,
                'image' => $message->image,
                'sender_id' => $message->sender_id,
                'created_at' => $message->created_at->format('H:i'),
            ]
        ]);
    }
}