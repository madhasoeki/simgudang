<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Barang;
use App\Models\Projek;
use App\Models\ProjekStatus;
use Illuminate\Http\Request;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransaksiKeluarController extends Controller
{
    public function index()
    {
        $transaksi = TransaksiKeluar::with(['barang', 'projek', 'projek.projekStatus'])
            ->latest()
            ->get();
            
        return view('transaksi_keluar.index', compact('transaksi'));
    }

    public function data(Request $request)
    {
        $query = TransaksiKeluar::with(['barang', 'projek'])
            ->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kode', function($row) {
                return $row->barang->kode;
            })
            ->addColumn('nama_barang', function($row) {
                return $row->barang->nama;
            })
            ->addColumn('satuan', function($row) {
                return $row->barang->satuan;
            })
            ->addColumn('tempat', function($row) {
                return $row->projek->nama;
            })
            ->editColumn('tanggal', function($row) {
                return $row->tanggal->format('Y-m-d');
            })
            ->rawColumns([])
            ->toJson();
    }

    public function create()
    {
        $projek = ProjeK::all();
        $barang = Barang::all();
        return view('transaksi_keluar.create', compact('projek', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'projek_id' => 'required|exists:projek,id',
            'barang_kode' => 'required|exists:barang,kode',
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Hitung jumlah
            $validated['jumlah'] = $validated['qty'] * $validated['harga'];

            // Simpan transaksi
            $transaksi = TransaksiKeluar::create($validated);

            // Update stok
            Stok::where('barang_kode', $validated['barang_kode'])
                ->decrement('jumlah', $validated['qty']);

            // Update projek status bulanan
            ProjekStatus::updateOrCreate(
                [
                    'projek_id' => $validated['projek_id'],
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
                ->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
