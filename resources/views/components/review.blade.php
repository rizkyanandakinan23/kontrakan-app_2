@php
    $reviews = $kamar->reviews ?? collect();

    $userBooking = auth()->check()
        ? \App\Models\Booking::where('user_id', auth()->id())
            ->where('kamar_id', $kamar->id)
            ->whereHas('payment', function ($query) {
                $query->where('status', 'success');
            })
            ->latest('tanggal_masuk')
            ->first()
        : null;
@endphp

<div id="review"></div>

<!-- REVIEW SECTION -->
<div class="bg-white rounded-3xl shadow-2xl mt-10 p-8">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <h2 class="text-2xl font-bold text-gray-800">
            Review Penghuni
        </h2>

        <div class="text-right">
            <p class="text-gray-500 text-sm">Total Review</p>
            <h3 class="text-xl font-bold text-amber-700">
                {{ $reviews->count() }}
            </h3>
        </div>

    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4">
            {{ session('error') }}
        </div>
    @endif

   <!-- FORM REVIEW -->
@auth

    @if(!$userBooking)

        {{-- BELUM PERNAH BOOKING --}}
        <div class="bg-gray-100 text-gray-500 px-4 py-3 rounded-xl mb-6">
            Anda harus menyewa kamar ini terlebih dahulu untuk memberikan review.
        </div>

        <button
            type="button"
            disabled
            class="bg-gray-300 text-gray-500 px-5 py-2 rounded-xl cursor-not-allowed mb-8">

            Kirim Review

        </button>

    @elseif(now()->startOfDay()->lt($userBooking->tanggal_masuk))

        {{-- SUDAH BOOKING, TAPI BELUM MASUK MASA SEWA --}}
        <div class="bg-yellow-100 text-yellow-700 px-4 py-3 rounded-xl mb-3">
            Anda belum memasuki masa sewa. Review dapat diberikan setelah masa sewa dimulai.
        </div>

        <button
            type="button"
            disabled
            class="bg-gray-300 text-gray-500 px-5 py-2 rounded-xl cursor-not-allowed mb-8">

            Kirim Review

        </button>

    @else

        {{-- SUDAH MEMASUKI MASA SEWA --}}
        <form action="{{ route('review.store', $kamar->id) }}"
              method="POST"
              class="mb-8">

            @csrf

            <div class="mb-3">
                <label class="text-sm font-semibold">
                    Rating
                </label>

                <select
                    name="rating"
                    class="w-full border rounded-xl px-3 py-2 mt-1">

                    <option value="5">⭐⭐⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="1">⭐</option>

                </select>
            </div>

            <div class="mb-3">

                <label class="text-sm font-semibold">
                    Komentar
                </label>

                <textarea
                    name="komentar"
                    rows="4"
                    class="w-full border rounded-xl px-3 py-2 mt-1"
                    placeholder="Tulis pengalaman Anda..."></textarea>

            </div>

            <button
                class="bg-amber-700 hover:bg-amber-800 text-white px-5 py-2 rounded-xl">

                Kirim Review

            </button>

        </form>

    @endif

@else

    <div class="bg-yellow-100 text-yellow-700 px-4 py-3 rounded-xl mb-6">

        Login untuk memberikan review.

    </div>

@endauth

    <!-- LIST REVIEW -->
    <div class="space-y-4">

        @forelse($reviews->sortByDesc('created_at') as $review)

            <div class="border rounded-2xl p-5">

                <div class="flex justify-between">

                    <div>

                        <h4 class="font-bold">
                            {{ $review->user->nama_lengkap ?? 'User' }}
                        </h4>

                        <div class="text-yellow-500 text-sm">
                            {{ str_repeat('⭐', $review->rating) }}
                        </div>

                    </div>

                    <span class="text-xs text-gray-400">
                        {{ $review->created_at->format('d M Y') }}
                    </span>

                </div>

                <p class="mt-2 text-gray-600">
                    {{ $review->komentar }}
                </p>

                @auth
                    <div class="mt-3 flex gap-2">

                        @if(auth()->id() == $review->user_id)

                            <form action="{{ route('review.destroy', $review->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-600 text-sm">
                                    Hapus
                                </button>
                            </form>

                        @else

                            <button
                                type="button"
                                onclick="openReportModal({{ $review->id }})"
                                class="text-yellow-600 text-sm">
                                Report
                            </button>

                        @endif

                    </div>
                @endauth

            </div>

        @empty

            <div class="text-center text-gray-500 py-10">
                Belum ada review
            </div>

        @endforelse

    </div>
</div>

<!-- MODAL REPORT -->
<div id="reportModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md p-6 rounded-2xl relative">

        <button onclick="closeReportModal()"
            class="absolute top-3 right-3 text-gray-500 text-xl">
            &times;
        </button>

        <h2 class="text-xl font-bold mb-4">Report Review</h2>

        <form id="reportForm" method="POST">
            @csrf
            @method('PATCH')

            <select name="alasan" class="w-full border rounded-xl p-2 mb-4">
                <option value="">Pilih alasan</option>
                <option value="Spam">Spam</option>
                <option value="Kasar">Kasar</option>
                <option value="Hoax">Hoax</option>
                <option value="Promosi">Promosi</option>
            </select>

            <button class="bg-yellow-500 text-white px-4 py-2 rounded-xl w-full">
                Kirim
            </button>

        </form>

    </div>
</div>

<script>
function openReportModal(id)
{
    document.getElementById('reportForm').action = `/review/${id}/report`;
    document.getElementById('reportModal').classList.remove('hidden');
    document.getElementById('reportModal').classList.add('flex');
}

function closeReportModal()
{
    document.getElementById('reportModal').classList.add('hidden');
    document.getElementById('reportModal').classList.remove('flex');
}
</script>