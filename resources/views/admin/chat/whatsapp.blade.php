@extends('layouts.adminlayouts')

@section('title', 'Admin Chat')

@section('content')



<div class="h-screen flex bg-gray-100 overflow-hidden">

    {{-- ========================= --}}
    {{-- SIDEBAR --}}
    {{-- ========================= --}}
    <div class="w-[340px] bg-white border-r flex flex-col">

        {{-- HEADER --}}
        <div class="bg-amber-500 text-white px-5 py-4 shadow">

            <h1 class="text-lg font-bold">
                💬 Chat User
            </h1>

            <p class="text-sm text-white/80">
                Kelola percakapan user
            </p>

        </div>

        {{-- LIST CHAT --}}
        <div class="flex-1 overflow-y-auto">

            @forelse($conversations as $conv)

                @php
                    $lastMessage = $conv->messages->last();
                @endphp

                <button
                    type="button"
                    onclick="loadConversation({{ $conv->id }})"
                    class="w-full text-left px-4 py-4 border-b hover:bg-gray-100 transition">

                    <div class="flex items-center gap-3">

                        {{-- AVATAR --}}
                        <div class="w-12 h-12 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold shrink-0">

                            {{ strtoupper(substr($conv->user->nama_lengkap ?? 'U', 0, 1)) }}

                        </div>

                        {{-- INFO --}}
                        <div class="flex-1 min-w-0">

                            <div class="font-semibold text-gray-800 truncate">
                                {{ $conv->user->nama_lengkap ?? 'User' }}
                            </div>

                            <div class="text-sm text-gray-500 truncate">

                                @if($lastMessage)

                                    {{ $lastMessage->message ?: '📷 Foto' }}

                                @else

                                    Belum ada pesan

                                @endif

                            </div>

                        </div>

                    </div>

                </button>

            @empty

                <div class="p-6 text-center text-gray-500">

                    Belum ada chat

                </div>

            @endforelse

        </div>

    </div>

    {{-- ========================= --}}
    {{-- CHAT AREA --}}
    {{-- ========================= --}}
    <div class="flex-1 flex flex-col">

        {{-- HEADER --}}
        <div id="chatHeader"
             class="bg-white border-b px-5 py-4">

            <div class="text-gray-700 font-semibold">

                Pilih chat user

            </div>

        </div>

        {{-- CHAT BOX --}}
        <div id="chatBox"
             class="flex-1 overflow-y-auto p-5 bg-gray-100">

            <div class="text-center text-gray-500 mt-10">

                Percakapan akan tampil di sini

            </div>

        </div>

        {{-- IMAGE PREVIEW --}}
        <div id="previewBox"
             class="hidden bg-white border-t px-4 py-2">

            <img id="previewImage"
                 class="max-h-[120px] rounded-lg border">

        </div>

        {{-- FORM --}}
        <form id="chatForm"
              class="bg-white border-t p-3 flex items-center gap-2">

            @csrf

            <input type="hidden"
                   id="conversationId">

            {{-- IMAGE --}}
            <input type="file"
                   id="imageInput"
                   class="hidden">

            <label for="imageInput"
                   class="cursor-pointer text-2xl">

                📷

            </label>

            {{-- INPUT --}}
            <input type="text"
                   id="messageInput"
                   placeholder="Tulis pesan..."
                   class="flex-1 border rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">

            {{-- EMOJI --}}
            <button type="button"
                    onclick="addEmoji('😊')"
                    class="text-xl">
                😊
            </button>

            <button type="button"
                    onclick="addEmoji('😂')"
                    class="text-xl">
                😂
            </button>

            <button type="button"
                    onclick="addEmoji('🔥')"
                    class="text-xl">
                🔥
            </button>

            {{-- SEND --}}
            <button type="submit"
                    class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2 rounded-full text-sm transition">

                Kirim

            </button>

        </form>

    </div>

</div>

{{-- ========================= --}}
{{-- SCRIPT --}}
{{-- ========================= --}}
<script>

let selectedConversation = null;

// =========================
// ELEMENT
// =========================

const chatBox =
    document.getElementById('chatBox');

const chatHeader =
    document.getElementById('chatHeader');

const form =
    document.getElementById('chatForm');

const imageInput =
    document.getElementById('imageInput');

const previewBox =
    document.getElementById('previewBox');

const previewImage =
    document.getElementById('previewImage');

const conversationIdInput =
    document.getElementById('conversationId');

const messageInput =
    document.getElementById('messageInput');


// =========================
// EMOJI
// =========================

function addEmoji(emoji)
{
    messageInput.value += emoji;

    messageInput.focus();
}


// =========================
// ESCAPE HTML
// =========================

function escapeHtml(text)
{
    if (!text) return '';

    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}


// =========================
// SCROLL BOTTOM
// =========================

function scrollBottom()
{
    setTimeout(() => {

        chatBox.scrollTop =
            chatBox.scrollHeight;

    }, 100);
}


// =========================
// RENDER MESSAGE
// =========================

function renderMessage(msg)
{
    return `

        <div class="flex ${
            msg.is_admin
                ? 'justify-end'
                : 'justify-start'
        } mb-3">

            <div class="
                max-w-[75%]
                px-4
                py-2
                rounded-2xl
                text-sm
                shadow-sm
                break-words

                ${
                    msg.is_admin
                        ? 'bg-amber-500 text-white'
                        : 'bg-white border text-gray-800'
                }
            ">

                ${
                    msg.message
                        ? `
                            <div class="whitespace-pre-wrap">
                                ${escapeHtml(msg.message)}
                            </div>
                        `
                        : ''
                }

                ${
                    msg.image
                        ? `
                            <img
                                src="/storage/${msg.image}"
                                class="
                                    mt-2
                                    rounded-xl
                                    max-w-[220px]
                                    border
                                "
                            >
                        `
                        : ''
                }

                <div class="
                    text-[10px]
                    mt-1
                    text-right

                    ${
                        msg.is_admin
                            ? 'text-white/70'
                            : 'text-gray-400'
                    }
                ">

                    ${msg.time ?? ''}

                </div>

            </div>

        </div>

    `;
}


// =========================
// LOAD CONVERSATION
// =========================

async function loadConversation(id)
{
    selectedConversation = id;

    conversationIdInput.value = id;

    chatBox.innerHTML = `

        <div class="text-center text-gray-500 mt-10">

            Loading chat...

        </div>

    `;

    try {

        const response = await fetch(

            `/admin/chat/open/${id}`,

            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }

        );

        if (!response.ok) {

            throw new Error(
                'HTTP ERROR ' + response.status
            );

        }

        const data = await response.json();

        // =========================
        // HEADER
        // =========================

        chatHeader.innerHTML = `

            <div class="flex items-center gap-3">

                <div class="
                    w-10
                    h-10
                    rounded-full
                    bg-amber-500
                    text-white
                    flex
                    items-center
                    justify-center
                    font-bold
                ">

                    ${data.user.nama.charAt(0).toUpperCase()}

                </div>

                <div>

                    <div class="font-semibold text-gray-800">

                        ${data.user.nama}

                    </div>

                    <div class="text-sm text-gray-500">

                        Percakapan aktif

                    </div>

                </div>

            </div>

        `;

        // RESET
        chatBox.innerHTML = '';

        // EMPTY
        if (!data.messages || data.messages.length === 0) {

            chatBox.innerHTML = `

                <div class="text-center text-gray-500 mt-10">

                    Belum ada pesan

                </div>

            `;

            return;
        }

        // LOOP
        data.messages.forEach(msg => {

            chatBox.innerHTML +=
                renderMessage(msg);

        });

        scrollBottom();

    } catch (error) {

        console.log(error);

        chatBox.innerHTML = `

            <div class="text-center text-red-500 mt-10">

                Gagal memuat chat

            </div>

        `;

    }
}


// =========================
// PREVIEW IMAGE
// =========================

imageInput.addEventListener('change', function () {

    const file = this.files[0];

    if (!file) {

        previewBox.classList.add('hidden');

        previewImage.src = '';

        return;
    }

    const reader = new FileReader();

    reader.onload = function (e) {

        previewImage.src =
            e.target.result;

        previewBox.classList.remove('hidden');

    };

    reader.readAsDataURL(file);

});


// =========================
// SEND CHAT
// =========================

form.addEventListener('submit', async function (e) {

    e.preventDefault();

    const conversationId =
        conversationIdInput.value;

    if (!conversationId) {

        alert('Pilih chat terlebih dahulu');

        return;
    }

    const message =
        messageInput.value.trim();

    const image =
        imageInput.files[0];

    if (message === '' && !image) {

        alert('Pesan atau gambar wajib diisi');

        return;
    }

    const formData = new FormData();

    formData.append('message', message);

    if (image) {

        formData.append('image', image);

    }

    try {

        const response = await fetch(

            `/admin/chat/send/${conversationId}`,

            {
                method: 'POST',

                headers: {

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,

                    'Accept': 'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'

                },

                body: formData

            }

        );

        if (!response.ok) {

            throw new Error(
                'HTTP ERROR ' + response.status
            );

        }

        const data = await response.json();

        if (!data.status) {

            alert(
                data.message ??
                'Gagal mengirim pesan'
            );

            return;
        }

        // HAPUS EMPTY MESSAGE
        if (
            chatBox.innerText.includes(
                'Belum ada pesan'
            )
        ) {

            chatBox.innerHTML = '';

        }

        // APPEND CHAT
        chatBox.innerHTML += renderMessage({

            message:
                data.data.message,

            image:
                data.data.image,

            time:
                data.data.created_at,

            is_admin: true

        });

        // RESET
        form.reset();

        previewImage.src = '';

        previewBox.classList.add('hidden');

        messageInput.value = '';

        scrollBottom();

    } catch (error) {

        console.log(error);

        alert(
            'Terjadi kesalahan'
        );

    }

});

</script>





@endsection