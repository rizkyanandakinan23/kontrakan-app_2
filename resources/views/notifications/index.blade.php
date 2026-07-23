@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold mb-6 text-white">
    Notifikasi
</h1>

    @forelse($notifications as $notif)

    @if(!empty($notif->data['url']))
        <a href="{{ $notif->data['url'] }}">
    @endif

    <div class="bg-white p-4 rounded-xl shadow mb-3 hover:bg-gray-50 transition cursor-pointer">

        <!-- TITLE -->
        <h3 class="font-semibold text-gray-800">
            {{ $notif->data['title'] ?? 'Notifikasi' }}
        </h3>

        <!-- MESSAGE -->
        <p class="text-gray-600 text-sm mt-1">
            {{ $notif->data['message'] ?? '-' }}
        </p>

        <!-- TIME -->
        <small class="text-gray-400">
            {{ $notif->created_at->diffForHumans() }}
        </small>

    </div>

    @if(!empty($notif->data['url']))
        </a>
    @endif

@empty

        <div class="bg-white p-4 rounded-xl shadow text-gray-500">
            Belum ada notifikasi
        </div>

    @endforelse

</div>

@endsection