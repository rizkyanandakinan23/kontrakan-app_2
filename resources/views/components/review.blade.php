@php
    $reviews = $kamar->reviews ?? collect();
@endphp

<!-- ================================= -->
<!-- REVIEW SECTION -->
<!-- ================================= -->

<div class="bg-white rounded-3xl shadow-2xl mt-10 p-8">

    <div class="flex items-center justify-between mb-8">

        <h2 class="text-3xl font-bold text-gray-800">
            Review Penghuni
        </h2>

        <div class="text-right">

            <p class="text-gray-500 text-sm">
                Total Review
            </p>

            <h3 class="text-2xl font-bold text-amber-700">
                {{ $reviews->count() }}
            </h3>

        </div>

    </div>

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-8">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-8">
            {{ session('error') }}
        </div>
    @endif

    <!-- VALIDATION -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-8">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ================================= -->
    <!-- FORM REVIEW -->
    <!-- ================================= -->

    @auth
        <form action="{{ route('review.store', $kamar->id) }}" method="POST" class="mb-12">
            @csrf

            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-3">
                    Rating
                </label>

                <select name="rating"
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-amber-500"
                >
                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4">⭐⭐⭐⭐ (4)</option>
                    <option value="3">⭐⭐⭐ (3)</option>
                    <option value="2">⭐⭐ (2)</option>
                    <option value="1">⭐ (1)</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-3">
                    Komentar
                </label>

                <textarea
                    name="komentar"
                    rows="5"
                    required
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-amber-500"
                    placeholder="Bagikan pengalaman Anda..."
                ></textarea>
            </div>

            <button type="submit"
                class="bg-amber-700 hover:bg-amber-800 text-white px-8 py-4 rounded-2xl font-bold"
            >
                Kirim Review
            </button>
        </form>
    @else
        <div class="bg-yellow-100 border border-yellow-200 text-yellow-700 px-5 py-4 rounded-2xl mb-10">
            Silakan login terlebih dahulu untuk memberikan review.
        </div>
    @endauth

    <!-- ================================= -->
    <!-- LIST REVIEW -->
    <!-- ================================= -->

    <div class="space-y-6">

        @forelse($reviews->sortByDesc('created_at') as $review)

            <div class="border border-gray-200 rounded-3xl p-6 hover:shadow-lg transition">

                <div class="flex justify-between mb-4">

                    <div>
                        <h4 class="font-bold text-lg">
                            {{ $review->user->name }}
                        </h4>

                        <div class="text-yellow-500">
                            @for($i = 1; $i <= $review->rating; $i++)
                                ⭐
                            @endfor
                        </div>
                    </div>

                    <span class="text-sm text-gray-400">
                        {{ $review->created_at->format('d M Y') }}
                    </span>

                </div>

                <p class="text-gray-600 mb-5">
                    {{ $review->komentar }}
                </p>

                @auth
                    <div class="flex gap-3">

                        @if(auth()->id() == $review->user_id)

                            <form action="{{ route('admin.review.delete', $review->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus review ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="bg-red-100 text-red-700 px-4 py-2 rounded-xl">
                                    Hapus
                                </button>
                            </form>

                        @else

                            <button
                                type="button"
                                onclick="openReportModal({{ $review->id }})"
                                class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-xl"
                            >
                                Report
                            </button>

                        @endif

                    </div>
                @endauth

            </div>

        @empty

            <div class="text-center py-16">
                <div class="text-6xl mb-4">⭐</div>
                <h3 class="text-2xl font-bold">Belum Ada Review</h3>
                <p class="text-gray-500">Jadilah yang pertama memberi review.</p>
            </div>

        @endforelse

    </div>
</div>

<!-- ================================= -->
<!-- MODAL REPORT -->
<!-- ================================= -->

<div id="reportModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-3xl w-full max-w-md p-8 relative">

        <button onclick="closeReportModal()"
            class="absolute top-4 right-4 text-gray-400 text-2xl"
        >
            &times;
        </button>

        <h2 class="text-2xl font-bold mb-6">Report Review</h2>

        <form id="reportForm" method="POST">
            @csrf
            @method('PATCH')

            <select name="alasan" class="w-full border rounded-2xl p-3 mb-6">
                <option value="">Pilih Alasan</option>
                <option value="Spam">Spam</option>
                <option value="Bahasa Kasar">Bahasa Kasar</option>
                <option value="Informasi Palsu">Informasi Palsu</option>
                <option value="Promosi">Promosi</option>
                <option value="Tidak Pantas">Tidak Pantas</option>
            </select>

            <div class="flex justify-end gap-3">

                <button type="button"
                    onclick="closeReportModal()"
                    class="bg-gray-200 px-4 py-2 rounded-xl"
                >
                    Batal
                </button>

                <button class="bg-yellow-500 text-white px-4 py-2 rounded-xl">
                    Kirim
                </button>

            </div>

        </form>

    </div>
</div>

<!-- ================================= -->
<!-- SCRIPT -->
<!-- ================================= -->

<script>

function openReportModal(reviewId)
{
    const modal = document.getElementById('reportModal');
    const form = document.getElementById('reportForm');

    form.action = `/review/${reviewId}/report`;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReportModal()
{
    const modal = document.getElementById('reportModal');

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

</script>