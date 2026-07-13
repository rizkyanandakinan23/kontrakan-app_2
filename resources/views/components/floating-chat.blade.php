@auth
@php
    $kamarId = request()->route('id');
@endphp

<div class="fixed bottom-6 right-6 z-50 flex items-end gap-2">

    <!-- KETERANGAN -->
    <div class="bg-black text-white text-xs px-3 py-2 rounded-lg shadow-lg">
        Butuh bantuan? Chat di sini
    </div>

    <!-- BUTTON CHAT -->
    <a href="{{ route('chat.index') . ($kamarId ? '?kamar_id=' . $kamarId : '') }}"
       class="w-14 h-14 bg-amber-500 hover:bg-amber-600 text-white rounded-full shadow-xl flex items-center justify-center text-2xl">
        💬
    </a>

</div>
@endauth