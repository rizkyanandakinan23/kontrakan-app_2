@auth
@php
    $kamarId = request()->route('id') ?? null;
@endphp

<div class="fixed bottom-6 right-6 z-50">

    <a href="{{ route('chat.index', $kamarId ? ['kamar_id' => $kamarId] : []) }}"
       class="w-14 h-14 bg-amber-500 hover:bg-amber-600 text-white rounded-full shadow-xl flex items-center justify-center text-2xl">
        💬
    </a>

</div>
@endauth