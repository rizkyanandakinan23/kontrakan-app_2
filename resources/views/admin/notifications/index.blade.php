@extends('layouts.adminlayouts')

@section('title', 'Notifikasi Admin')

@section('content')

<div class="max-w-5xl mx-auto">

    <h1 class="text-3xl font-bold text-white mb-6">
        Notifikasi Admin
    </h1>

 @forelse($notifications as $notif)

    @if(!empty($notif->data['url']))
        <a href="{{ $notif->data['url'] }}" class="block">
    @endif

    <div class="bg-white p-5 rounded-2xl shadow mb-4 border-l-4 border-amber-500 hover:bg-gray-50 transition cursor-pointer">

        <div class="flex justify-between items-start">

            <div>
                <h3 class="font-semibold text-gray-800">
                    {{ $notif->data['title'] ?? 'Notifikasi' }}
                </h3>

                <p class="text-gray-600 text-sm mt-1">
                    {{ $notif->data['message'] ?? '-' }}
                </p>
            </div>

            <span class="text-xs text-gray-400">
                {{ $notif->created_at->diffForHumans() }}
            </span>

        </div>

    </div>

    @if(!empty($notif->data['url']))
        </a>
    @endif

@empty

<div class="bg-white p-5 rounded-xl shadow text-gray-500">
    Tidak ada notifikasi
</div>

@endforelse

</div>

@endsection