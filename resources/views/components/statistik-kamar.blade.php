<div class="bg-white rounded-2xl shadow-lg p-4 mb-6">

    <h3 class="text-base font-semibold text-gray-800 mb-1">
        Statistik Ketersediaan
    </h3>

    <p class="text-sm text-gray-500 mb-4">
        Per tanggal
        <span class="font-semibold">
            {{ \Carbon\Carbon::parse($tanggal ?? now())->format('d M Y') }}
        </span>
    </p>

    <div class="grid grid-cols-3 gap-3 text-center">

        <div>
            <div class="text-3xl mb-1">🏠</div>
            <h2 class="text-2xl font-bold text-amber-700">
                {{ $totalKamar }}
            </h2>
            <p class="text-sm text-gray-500">
                Total Kontrakan
            </p>
        </div>

        <div>
            <div class="text-3xl mb-1">✅</div>
            <h2 class="text-2xl font-bold text-green-600">
                {{ $kamarKosong }}
            </h2>
            <p class="text-sm text-gray-500">
                Tersedia
            </p>
        </div>

        <div>
            <div class="text-3xl mb-1">🔒</div>
            <h2 class="text-2xl font-bold text-red-600">
                {{ $kamarTerisi }}
            </h2>
            <p class="text-sm text-gray-500">
                Terisi
            </p>
        </div>

    </div>

</div>