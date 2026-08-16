@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">
            Notifikasi
        </h1>
    </div>

    {{-- NOTIFICATION LIST --}}
    @forelse($notifications as $notif)

        @php
            $title = $notif->data['title'] ?? '';

            switch ($title) {

                case 'Peringatan Masa Sewa':
                    $url = route('booking.riwayat');
                    break;

                case 'Pembayaran Berhasil':
                    $url = route('booking.riwayat');
                    break;

                case 'Balasan Chat':
                    $url = route('chat.index');
                    break;

                default:
                    $url = $notif->data['url'] ?? null;
                    break;

                case 'Peringatan Pembayaran':
                    $url = route('booking.riwayat');
                    break;
            }
        @endphp

        <div class="relative bg-white rounded-xl shadow mb-4 overflow-hidden">

            {{-- Dropdown --}}
            <div class="absolute top-4 right-4 z-20">

                <details class="relative">

                    <summary class="list-none cursor-pointer text-2xl text-gray-500 hover:text-gray-700 select-none">
                        ⋮
                    </summary>

                    <div class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl border">

                        <form action="{{ route('notifications.destroy', $notif->id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus notifikasi ini?')">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="w-full text-left px-4 py-3 hover:bg-red-50 text-red-600">
                                🗑 Hapus
                            </button>

                        </form>

                    </div>

                </details>

            </div>

            {{-- Card Klik --}}
            @if($url)

                <a href="{{ $url }}"
                   class="block p-5 pr-16 hover:bg-gray-50 transition">

            @else

                <div class="p-5 pr-16">

            @endif

                    <h3 class="font-semibold text-lg text-gray-800">
                        {{ $notif->data['title'] ?? 'Notifikasi' }}
                    </h3>

                    <p class="text-gray-600 mt-2">
                        {{ $notif->data['message'] ?? '-' }}
                    </p>

                    <p class="text-sm text-gray-400 mt-3">
                        {{ $notif->created_at->diffForHumans() }}
                    </p>

            @if($url)

                </a>

            @else

                </div>

            @endif

        </div>

    @empty

        <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
            Belum ada notifikasi.
        </div>

    @endforelse

</div>

@endsection