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
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create', [
            'title' => 'Tambah Barang Baru'
        ]);
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
}
