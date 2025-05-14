<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // Total barang masuk hari ini
        $barangMasukHariIni = TransaksiMasuk::whereDate('tanggal', today())->sum('qty');

        // Total barang keluar hari ini
        $barangKeluarHariIni = TransaksiKeluar::whereDate('tanggal', today())->sum('qty');

        // Barang dengan stok <= 10
        $stokMenipis = Barang::whereHas('stok', function ($query) {
            $query->where('jumlah', '<=', 10);
        })->get();

        // Transaksi masuk hari ini
        $transaksiMasukHariIni = TransaksiMasuk::with(['barang' => function($q) {
            $q->withTrashed();
        }])->whereDate('tanggal', today())->get();

        // Transaksi keluar hari ini
        $transaksiKeluarHariIni = TransaksiKeluar::with([
            'barang' => function($q) { $q->withTrashed(); },
            'tempat' => function($q) { $q->withTrashed(); }
        ])->whereDate('tanggal', today())->get();

        // Load quotes from JSON file
        $quotes = json_decode(file_get_contents(resource_path('quotes.json')), true);

        // Pilih quote secara acak
        $selectedQuote = $quotes[array_rand($quotes)];

        // Kirim data ke view
        return view('dashboard', [
            'title' => 'Selamat datang, ' . Auth::user()->name,
            'barangMasukHariIni' => $barangMasukHariIni,
            'barangKeluarHariIni' => $barangKeluarHariIni,
            'stokMenipis' => $stokMenipis,
            'transaksiMasukHariIni' => $transaksiMasukHariIni,
            'transaksiKeluarHariIni' => $transaksiKeluarHariIni,
            'quoteText' => $selectedQuote['text'],
            'quoteSource' => $selectedQuote['source'],
        ]);
    }
}
