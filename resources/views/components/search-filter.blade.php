<div class="bg-white rounded-3xl shadow-xl p-6 mb-8">


<form method="GET" action="{{ route('kamar.index') }}">

    <div class="flex flex-col md:flex-row gap-4">

        <!-- SEARCH -->
        <div class="flex-1">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama kamar atau deskripsi..."
                class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:outline-none"
            >
        </div>

        <!-- STATUS -->
        <div>
            <select
                name="status"
                class="w-full md:w-44 border border-gray-300 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:outline-none"
            >
                <option value="">Semua Status</option>

                <option
                    value="kosong"
                    {{ request('status') == 'kosong' ? 'selected' : '' }}
                >
                    Tersedia
                </option>

                <option
                    value="terisi"
                    {{ request('status') == 'terisi' ? 'selected' : '' }}
                >
                    Terisi
                </option>
            </select>
        </div>

        <!-- SORT -->
        <div>
            <select
                name="sort"
                class="w-full md:w-44 border border-gray-300 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:outline-none"
            >
                <option value="">Urutkan Harga</option>

                <option
                    value="murah"
                    {{ request('sort') == 'murah' ? 'selected' : '' }}
                >
                    Harga Termurah
                </option>

                <option
                    value="mahal"
                    {{ request('sort') == 'mahal' ? 'selected' : '' }}
                >
                    Harga Termahal
                </option>
            </select>
        </div>

        <!-- BUTTON -->
        <div class="flex gap-2">

            <button
                type="submit"
                class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-3 rounded-2xl font-semibold transition"
            >
                Cari
            </button>

            <a
                href="{{ route('kamar.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-2xl font-semibold transition"
            >
                Reset
            </a>

        </div>

    </div>

</form>

</div>
