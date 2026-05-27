<!-- FLOATING CHAT BUTTON -->
<div class="fixed bottom-6 right-6 z-50">

    <!-- BUTTON -->
    <button
        onclick="toggleChat()"
        class="bg-amber-700 hover:bg-amber-800 text-white w-14 h-14 rounded-full shadow-lg flex items-center justify-center text-2xl"
    >
        💬
    </button>

    <!-- CHAT BOX -->
    <div
        id="chatBox"
        class="hidden w-80 h-96 bg-white rounded-2xl shadow-2xl mt-3 flex flex-col overflow-hidden"
    >

        <!-- HEADER -->
        <div class="bg-amber-700 text-white p-4 font-bold flex justify-between items-center">
            Chat Support

            <button onclick="toggleChat()" class="text-white text-lg">
                ✕
            </button>
        </div>

        <!-- MESSAGE AREA -->
        <div class="flex-1 p-3 overflow-y-auto space-y-2 text-sm">
            <div class="bg-gray-100 p-2 rounded-lg w-fit">
                Halo 👋 Ada yang bisa dibantu?
            </div>
        </div>

        <!-- INPUT -->
        <div class="p-3 border-t flex gap-2">
            <input
                type="text"
                placeholder="Tulis pesan..."
                class="flex-1 border rounded-xl px-3 py-2 text-sm"
            >
            <button class="bg-amber-700 text-white px-3 rounded-xl">
                Kirim
            </button>
        </div>

    </div>
</div>

<script>
function toggleChat()
{
    const box = document.getElementById('chatBox');
    box.classList.toggle('hidden');
}
</script>