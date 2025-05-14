<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TransaksiMasukController extends Controller
{
    public function index()
    {
        return view('transaksi_masuk.index');
    }

    public function data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Format tanggal tidak valid'], 400);
        }

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = TransaksiMasuk::with(['barang' => function($q) {
            $q->withTrashed();
        }])
        ->whereBetween('tanggal', [$startDate, $endDate]);

        return DataTables::of($query)
        ->addIndexColumn()
        // Ambil 'barang_kode' dan alias sebagai 'kode'
        ->addColumn('kode', function($row) {
            return $row->barang_kode; // Kolom yang sebenarnya ada di database
        })
        ->addColumn('nama_barang', function($row) {
            return $row->barang->nama ?? '-';
        })
        ->addColumn('satuan', function($row) {
            return $row->barang->satuan ?? '-';
        })
        ->editColumn('harga', function($row) {
            return $row->harga;
        })
        ->editColumn('jumlah', function($row) {
            return $row->jumlah;
        })
        ->rawColumns([])
        ->toJson();
    }

    public function create()
    {
        $barang = Barang::all();
        return view('transaksi_masuk.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'barang_kode' => 'required|exists:barang,kode',
            'qty' => 'required|numeric|min:1',
            'harga' => 'required|numeric|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Pastikan harga integer
            $validated['harga'] = (int) $validated['harga'];
            // Hitung jumlah juga integer
            $validated['jumlah'] = (int) ($validated['qty'] * $validated['harga']);

            // Tambahkan user_id
            $validated['user_id'] = Auth::id();

            // Simpan transaksi
            $transaksi = TransaksiMasuk::create($validated);

            // Update stok
            Stok::updateOrCreate(
                ['barang_kode' => $validated['barang_kode']],
                ['jumlah' => DB::raw("jumlah + {$validated['qty']}")]
            );

            DB::commit();

            return redirect()->route('transaksi-masuk.index')
                ->with('success', 'Transaksi masuk berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
}