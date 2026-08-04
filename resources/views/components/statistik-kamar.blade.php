<div class="bg-white rounded-2xl shadow-lg p-5 mb-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-5">

        <div>
            <h3 class="text-lg font-bold text-gray-800">
                Ketersediaan Kontrakan
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                Data ketersediaan per
                <span class="font-semibold text-gray-700">
                    {{ \Carbon\Carbon::parse($tanggal ?? now())->format('d M Y') }}
                </span>
            </p>
        </div>

        <div class="text-3xl">
            🏠
        </div>

    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- TOTAL KONTRAKAN --}}
        <div class="bg-amber-50 rounded-2xl p-5 text-center border border-amber-100">

            <div class="text-3xl mb-2">
                🏠
            </div>

            <h2 class="text-3xl font-bold text-amber-700">
                {{ $totalKamar }}
            </h2>

            <p class="text-sm font-medium text-gray-600 mt-1">
                Total Kontrakan
            </p>

        </div>

        {{-- TERSEDIA --}}
        <div class="bg-green-50 rounded-2xl p-5 text-center border border-green-100">

            <div class="text-3xl mb-2">
                ✅
            </div>

            <h2 class="text-3xl font-bold text-green-600">
                {{ $kamarKosong }}
            </h2>

            <p class="text-sm font-medium text-gray-600 mt-1">
                Kontrakan Tersedia
            </p>

        </div>

    </div>

</div>