@extends('layouts.app')

@section('title', 'Chat')

@section('content')

<div class="flex flex-col h-screen bg-gray-100">

    {{-- HEADER --}}
    <div class="bg-amber-500 text-white px-5 py-3">
        <h1 class="text-base font-semibold">💬 Chat Support</h1>
        <p class="text-xs text-white/90">Konsultasi kamar, booking, pembayaran</p>
    </div>

    {{-- CHAT BOX --}}
    <div id="chatBox" class="flex-1 overflow-y-auto px-3 py-3 space-y-2">

        @foreach($messages as $msg)

            @php
                $isMe = $msg->sender_id == auth()->id();
                $text = trim($msg->message ?? '');
            @endphp

            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">

                <div class="
                    text-sm px-3 py-2 rounded-2xl max-w-[70%]
                    {{ $isMe ? 'bg-amber-500 text-white' : 'bg-white border' }}
                ">

                    @if($text !== '')
                        <div class="mb-1">{{ $text }}</div>
                    @endif

                    @if($msg->image)
                        <img src="{{ asset('storage/'.$msg->image) }}"
                             class="mt-1 rounded-lg max-w-[160px]">
                    @endif

                </div>
            </div>

        @endforeach

    </div>

    {{-- INPUT --}}
    <form id="chatForm"
          class="bg-white border-t px-2 py-2 flex items-center gap-2">

        @csrf

        {{-- TEXT --}}
        <input type="text"
               id="messageInput"
               name="message"
               placeholder="Tulis pesan..."
               class="flex-1 text-sm border rounded-full px-3 py-2">

        {{-- FILE --}}
        <input type="file" name="image" id="imageInput" class="hidden">

        <label for="imageInput" class="cursor-pointer text-lg px-1">📷</label>

        {{-- EMOJI --}}
        <button type="button" onclick="addEmoji('😊')">😊</button>
        <button type="button" onclick="addEmoji('😂')">😂</button>
        <button type="button" onclick="addEmoji('😍')">😍</button>
        <button type="button" onclick="addEmoji('🔥')">🔥</button>

        {{-- SEND --}}
        <button type="submit"
                class="bg-amber-500 text-white text-sm px-3 py-2 rounded-full">
            Kirim
        </button>

    </form>

    {{-- IMAGE PREVIEW --}}
    <div id="imagePreviewBox" class="hidden px-3 pb-2">
        <img id="imagePreview" class="max-h-[120px] rounded-lg border">
    </div>

</div>

{{-- SCRIPT --}}
<script>
const form = document.getElementById('chatForm');
const chatBox = document.getElementById('chatBox');

const fileInput = document.getElementById('imageInput');
const previewBox = document.getElementById('imagePreviewBox');
const previewImg = document.getElementById('imagePreview');

function addEmoji(emoji) {
    const input = document.getElementById('messageInput');
    input.value += emoji;
    input.focus();
}

function scrollBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

// initial scroll
scrollBottom();

// PREVIEW IMAGE
fileInput.addEventListener('change', function () {
    const file = this.files[0];

    if (!file) {
        previewBox.classList.add('hidden');
        previewImg.src = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        previewImg.src = e.target.result;
        previewBox.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
});

// SEND MESSAGE
form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const messageInput = document.getElementById('messageInput');

    let formData = new FormData();
    formData.append('message', messageInput.value);

    if (fileInput.files && fileInput.files[0]) {
        formData.append('image', fileInput.files[0]);
    }

    const res = await fetch("{{ route('chat.send') }}", {
        method: "POST",
        body: formData,
        credentials: "same-origin",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });

    const data = await res.json();

    if (!data.status) {
        alert(data.message ?? "Gagal kirim pesan");
        return;
    }

    const msg = data.data;
    const text = (msg.message ?? '').trim();

    chatBox.insertAdjacentHTML('beforeend', `
        <div class="flex justify-end">
            <div class="bg-amber-500 text-white text-sm px-3 py-2 rounded-2xl max-w-[70%]">

                ${text ? `<div class="mb-1">${text}</div>` : ''}

                ${msg.image
                    ? `<img src="/storage/${msg.image}" class="mt-1 rounded-lg max-w-[160px]">`
                    : ''
                }

            </div>
        </div>
    `);

    // RESET
    form.reset();
    fileInput.value = '';

    previewBox.classList.add('hidden');
    previewImg.src = '';

    scrollBottom();
});
</script>

@endsection