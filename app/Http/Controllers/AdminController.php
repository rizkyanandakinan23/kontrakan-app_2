<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ======================
    // DASHBOARD ADMIN
    // ======================

    public function index()
    {
        $totalUser = User::count();

        $totalKamar = Kamar::count();

        $kamarTerisi = Kamar::where('status', 'terisi')->count();

        $users = User::latest()->take(5)->get();

        return view('admin.adminpanel', compact(
            'totalUser',
            'totalKamar',
            'kamarTerisi',
            'users'
        ));
    }

    // ======================
    // LIST KAMAR
    // ======================

    public function kamarIndex()
    {
        $kamars = Kamar::latest()->get();

        return view('admin.kamar.index', compact('kamars'));
    }

    // ======================
    // FORM TAMBAH
    // ======================

    public function kamarCreate()
    {
        return view('admin.kamar.create');
    }

    // ======================
    // SIMPAN KAMAR
    // ======================

    public function kamarStore(Request $request)
{
    $request->validate([
        'nama_kamar'   => 'required',
        'deskripsi'    => 'nullable',
        'harga'        => 'required|numeric',
        'fasilitas'    => 'nullable|array',
        'foto_kamar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $fotoPaths = [];

    /*
    |------------------------------------------------------------------
    | Upload Foto
    |------------------------------------------------------------------
    */

    if ($request->hasFile('foto_kamar')) {

        foreach ($request->file('foto_kamar') as $foto) {

            $path = $foto->store('kamar', 'public');

            $fotoPaths[] = $path;
        }
    }

    /*
    |------------------------------------------------------------------
    | Simpan Data
    |------------------------------------------------------------------
    */

    Kamar::create([

        'nama_kamar' => $request->nama_kamar,

        'deskripsi' => $request->deskripsi,

        'harga' => $request->harga,

        'status' => 'kosong',

        'fasilitas' => $request->fasilitas ?? [],

        'foto_kamar' => $fotoPaths,

    ]);

    return redirect()
        ->route('admin.kamar.index')
        ->with('success', 'Kamar berhasil ditambahkan');
}

    // ======================
    // FORM EDIT
    // ======================

    public function kamarEdit(Kamar $kamar)
    {
        return view('admin.kamar.edit', compact('kamar'));
    }

    // ======================
    // UPDATE KAMAR
    // ======================

    public function kamarUpdate(Request $request, Kamar $kamar)
    {
        $request->validate([
            'nama_kamar'   => 'required',
            'deskripsi'    => 'nullable',
            'harga'        => 'required|numeric',
            'fasilitas'    => 'nullable|array',

            'foto_kamar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Ambil Foto Lama
        |--------------------------------------------------------------------------
        */

        $fotoLama = $kamar->foto_kamar ?? [];

        if (!is_array($fotoLama)) {
            $fotoLama = [];
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Foto Baru
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto_kamar')) {

            // hapus foto lama
            foreach ($fotoLama as $foto) {

                if (Storage::disk('public')->exists($foto)) {

                    Storage::disk('public')->delete($foto);
                }
            }

            $fotoBaru = [];

            foreach ($request->file('foto_kamar') as $foto) {

                $path = $foto->store('kamar', 'public');

                $fotoBaru[] = $path;
            }

        } else {

            $fotoBaru = $fotoLama;
        }

        

        /*
        |--------------------------------------------------------------------------
        | Update Data
        |--------------------------------------------------------------------------
        */

        $kamar->update([

    'nama_kamar' => $request->nama_kamar,

    'deskripsi' => $request->deskripsi,

    'harga' => $request->harga,

    'status' => $request->status,

    'fasilitas' => $request->fasilitas ?? [],

    'foto_kamar' => $fotoBaru,

]);

        return redirect()
            ->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil diupdate');
    }

    // ======================
    // HAPUS KAMAR
    // ======================

    public function kamarDestroy(Kamar $kamar)
    {
        /*
        |--------------------------------------------------------------------------
        | Hapus Foto
        |--------------------------------------------------------------------------
        */

        $fotos = $kamar->foto_kamar ?? [];

        if (is_array($fotos)) {

            foreach ($fotos as $foto) {

                if (Storage::disk('public')->exists($foto)) {

                    Storage::disk('public')->delete($foto);
                }
            }
        }

        $kamar->delete();

        return back()->with('success', 'Kamar berhasil dihapus');
    }
}
