<?php

namespace App\Http\Controllers;

use App\Models\Tempat;
use Illuminate\Http\Request;

class TempatController extends Controller
{
    public function index()
    {
        $tempats = Tempat::orderBy('nama', 'asc')->get(); // Urutkan berdasarkan nama tempat A - Z
        $title = 'Daftar Tempat'; // Judul halaman

        return view('tempat.index', compact('tempats', 'title'));
    }

    public function createTempat()
    {
        $title = 'Tambah Tempat';
        return view('tempat.create', compact('title'));
    }

    public function storeTempat(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:tempat,nama|max:255'
        ]);

        Tempat::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('tempat.index')->with('success', 'Tempat berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tempat = Tempat::findOrFail($id);

        $title = 'Edit Nama Tempat'; // Judul halaman

        // Kirim data ke view edit
        return view('tempat.edit', compact('tempat', 'title'));
    }

    // Menyimpan perubahan tempat
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|unique:tempat,nama|max:255'
        ]);

        // Ambil data tempat yang akan diupdate
        $tempat = Tempat::findOrFail($id);

        // Update data tempat
        $tempat->update([
            'nama' => $request->nama,
        ]);

        // Redirect setelah berhasil update
        return redirect()->route('tempat.index')->with('success', 'Tempat berhasil diperbarui!');
    }

    public function list()
    {
        $tempat = Tempat::orderBy('nama', 'asc')->get(['id', 'nama']);
        return response()->json($tempat);
    }
}
