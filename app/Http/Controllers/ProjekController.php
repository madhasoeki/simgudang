<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use Illuminate\Http\Request;

class ProjekController extends Controller
{
    public function index()
    {
        $projeks = Projek::orderBy('nama', 'asc')->get(); // Urutkan berdasarkan nama projek A - Z
        $title = 'Daftar Projek'; // Judul halaman

        return view('projek.index', compact('projeks', 'title'));
    }

    public function createProjek()
    {
        $title = 'Tambah Projek';
        return view('projek.create', compact('title'));
    }

    public function storeProjek(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:projek,nama|max:255'
        ]);

        Projek::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('projek.index')->with('success', 'Projek berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $projek = Projek::findOrFail($id);

        $title = 'Edit Nama Projek'; // Judul halaman

        // Kirim data barang ke view edit
        return view('projek.edit', compact('projek', 'title'));
    }

    // Menyimpan perubahan barang
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|unique:projek,nama|max:255'
        ]);

        // Ambil data barang yang akan diupdate
        $barang = Projek::findOrFail($id);

        // Update data barang
        $barang->update([
            'nama' => $request->nama,
        ]);

        // Redirect setelah berhasil update
        return redirect()->route('projek.index')->with('success', 'Projek berhasil diperbarui!');
    }
}
