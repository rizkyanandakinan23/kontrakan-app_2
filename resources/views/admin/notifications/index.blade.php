@extends('layouts.adminlayouts')

@section('title', 'Notifikasi Admin')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <h1 class="text-3xl font-bold text-white mb-6">
        Notifikasi Admin
    </h1>

    {{-- NOTIFICATION LIST --}}
    @forelse($notifications as $notif)

        @php
            $title = $notif->data['title'] ?? '';

            /*
            |--------------------------------------------------------------------------
            | URL NOTIFIKASI ADMIN
            |--------------------------------------------------------------------------
            | Prioritaskan route berdasarkan jenis notifikasi.
            | Ini mencegah notifikasi lama yang masih menyimpan URL ngrok
            | diarahkan ke alamat lama.
            |--------------------------------------------------------------------------
            */

            switch ($title) {

                // Booking baru / booking masuk
                case 'Booking Baru':
                    $url = route('admin.booking.index');
                    break;

                // Pembayaran berhasil
                case 'Pembayaran Berhasil':
                    $url = route('admin.pembayaran.index');
                    break;

                // Review baru
                case 'Review Baru':
                    $url = route('admin.review.index');
                    break;

                // Pesan / balasan chat
                case 'Balasan Chat':
                    $url = route('admin.chat.index');
                    break;

                // Review dilaporkan oleh user
                case 'Review Dilaporkan':
                    $url = route('admin.review.index');
                    break;

                // User mengirim pesan chat
                case 'Pesan Chat Baru':
                    $url = route('admin.chat.index');
                    break;

                // Jika notifikasi tidak dikenali
                default:
                    // Gunakan URL yang tersimpan jika tersedia
                    $url = $notif->data['url'] ?? null;
                    break;
            }
        @endphp


        {{-- JIKA NOTIFIKASI MEMILIKI URL --}}
        @if($url)
            <a
                href="{{ $url }}"   class="block" >
        @endif

            {{-- NOTIFICATION CARD --}}
            <div
                class="
                    bg-white  p-5   rounded-2xl    shadow  mb-4  border-l-4   border-amber-500  hover:bg-gray-50 transition
                    {{ $url ? 'cursor-pointer' : '' }}  " >

                <div class="flex justify-between items-start gap-4">

                    {{-- NOTIFICATION CONTENT --}}
                    <div>

                        {{-- TITLE --}}
                        <h3 class="font-semibold text-gray-800">
                            {{ $notif->data['title'] ?? 'Notifikasi' }}
                        </h3>

                        {{-- MESSAGE --}}
                        <p class="text-gray-600 text-sm mt-1">
                            {{ $notif->data['message'] ?? '-' }}
                        </p>

                    </div>

                    {{-- TIME --}}
                    <span class="text-xs text-gray-400 whitespace-nowrap">
                        {{ $notif->created_at->diffForHumans() }}
                    </span>

                    <div class="mt-3 flex justify-end">

                        <form action="{{ route('admin.notifications.destroy', $notif->id) }}"
                            method="POST"
                            onsubmit="return confirm('Hapus notifikasi ini?')">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="text-red-600 hover:text-red-700"
                                title="Hapus">
                                🗑
                            </button>

                        </form>

                    </div>
                </div>
            </div>

        {{-- TUTUP LINK --}}
        @if($url)
            </a>
        @endif

    {{-- EMPTY --}}
    @empty
        <div class="bg-white p-5 rounded-xl shadow text-gray-500">
            Tidak ada notifikasi
        </div>
    @endforelse
</div>
@endsection