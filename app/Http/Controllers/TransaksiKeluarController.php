<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stok;
use App\Models\Barang;
use App\Models\Tempat;
use App\Models\TempatStatus;
use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class TransaksiKeluarController extends Controller
{
    public function index()
    {
        $transaksi = TransaksiKeluar::with(['barang', 'tempat', 'tempat.tempatStatus'])
            ->latest()
            ->get();
            
        return view('transaksi_keluar.index', compact('transaksi'));
    }

    public function data(Request $request)
    {
        $query = TransaksiKeluar::with([
            'barang' => function($q) {
                $q->withTrashed();
            },
            'tempat' => function($q) {
                $q->withTrashed();
            }
        ])
        ->whereBetween('tanggal', [
            $request->start_date,
            $request->end_date
        ]);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kode', function($row) {
                return $row->barang->kode ?? '-';
            })
            ->addColumn('nama_barang', function($row) {
                return $row->barang->nama ?? '-';
            })
            ->addColumn('satuan', function($row) {
                return $row->barang->satuan ?? '-';
            })
            ->addColumn('tempat', function($row) {
                return $row->tempat->nama ?? '-';
            })
            ->editColumn('tanggal', function($row) {
                return $row->tanggal->format('Y-m-d');
            })
            ->rawColumns([])
            ->toJson();
    }

    public function create()
    {
        $tempat = Tempat::all();
        $barang = Barang::all();
        return view('transaksi_keluar.create', compact('tempat', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tempat_id' => 'required|exists:tempat,id',
            'barang_kode' => 'required|exists:barang,kode',
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string'
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
            $transaksi = TransaksiKeluar::create($validated);

            // Update stok
            Stok::where('barang_kode', $validated['barang_kode'])
                ->decrement('jumlah', $validated['qty']);

            // Update tempat status bulanan
            TempatStatus::updateOrCreate(
                [
                    'tempat_id' => $validated['tempat_id'],
                    'tahun' => date('Y', strtotime($validated['tanggal'])),
                    'bulan' => date('n', strtotime($validated['tanggal']))
                ],
                [
                    'total' => DB::raw("total + {$validated['jumlah']}"),
                    'status' => 'loading' // Default status
                ]
            );

            DB::commit();

            return redirect()->route('transaksi-keluar.index')
                ->with('success', 'Transaksi Keluar berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
    
    public function laporanTempatData(Request $request)
    {
        if (!$request->tempat_id) {
            return DataTables::of([])->toJson();
        }
    
        $month = $request->month ?? now()->format('Y-m');
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();
    
        // Query dengan filter tempat_id DAN date range
        $query = TransaksiKeluar::with(['barang', 'tempat'])
            ->where('tempat_id', $request->tempat_id)
            ->whereBetween('tanggal', [$start, $end]);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kode', function ($row) {
                return $row->barang->kode;
            })
            ->addColumn('nama_barang', function ($row) {
                return $row->barang->nama;
            })
            ->addColumn('satuan', function ($row) {
                return $row->barang->satuan;
            })
            ->addColumn('tempat_nama', function ($row) {
                return $row->tempat->nama;
            })
            ->editColumn('tanggal', function ($row) {
                return $row->tanggal->format('d/m/Y');
            })
            ->editColumn('harga', function ($row) {
                return 'Rp' . number_format($row->harga, 0, ',', '.');
            })
            ->editColumn('jumlah', function ($row) {
                return 'Rp' . number_format($row->jumlah, 0, ',', '.');
            })
            ->rawColumns([])
            ->toJson();
    }

    public function hargaBarang(Request $request)
    {
        $barang_kode = $request->barang_kode;
        $hargaList = TransaksiMasuk::where('barang_kode', $barang_kode)
            ->select('harga')
            ->distinct()
            ->orderByDesc('harga')
            ->pluck('harga')
            ->toArray();

        return response()->json($hargaList);
    }
}
