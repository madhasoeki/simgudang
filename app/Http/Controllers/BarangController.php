<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('stok')->get();
        $barangs = Barang::orderBy('kode', 'asc')->get(); // mengurutkan berdasarkan kode
        $title = 'Daftar Barang'; // Judul halaman
        return view('barang.index', compact('barangs', 'title'));
    }

    public function create()
    {
        $title = 'Tambah Barang Baru'; // Judul halaman

        return view('barang.create', compact('title'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'kode' => 'required|unique:barang,kode|max:10',
            'nama' => 'required|max:100',
            'satuan' => 'required|max:20'
        ]);

        // Simpan ke database
        $barang = Barang::create($validated);
        
        // Buat stok awal
        $barang->stok()->create(['jumlah' => 0]);

        return redirect()->route('barang.index')
                        ->with('success', 'Barang '.$barang->nama.' berhasil ditambahkan!');
    }

    // Menampilkan form edit barang
    public function edit($kode)
    {
        // Ambil data barang berdasarkan kode
        $barang = Barang::findOrFail($kode);

        $title = 'Edit Data Barang'; // Judul halaman

        // Kirim data barang ke view edit
        return view('barang.edit', compact('barang', 'title'));
    }

    // Menyimpan perubahan barang
    public function update(Request $request, $kode)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:100',
            'satuan' => 'required|string|max:20',
        ]);

        // Ambil data barang yang akan diupdate
        $barang = Barang::findOrFail($kode);

        // Update data barang
        $barang->update([
            'nama' => $request->nama,
            'satuan' => $request->satuan,
        ]);

        // Redirect setelah berhasil update
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }
}
