<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stok;
use App\Models\Opname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OpnameController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        
        // Generate data untuk semua barang yang belum ada di bulan ini
        Artisan::call('opname:generate', ['month' => $month]);

        return view('opname.index', [
            'title' => 'Stock Opname',
            'currentMonth' => $month
        ]);
    }

    private function opnameDataExists($month)
    {
        return Opname::whereYear('periode_awal', Carbon::parse($month))
            ->whereMonth('periode_awal', Carbon::parse($month))
            ->exists();
    }

    public function data(Request $request)
    {
        $month = $request->month ?? date('Y-m');
        $year = date('Y', strtotime($month));
        $monthNumber = date('m', strtotime($month));

        $query = Opname::with(['barang' => function($q) {
            $q->withTrashed();
        }])
            ->whereYear('periode_awal', $year)
            ->whereMonth('periode_awal', $monthNumber)
            ->select('opname.*');

        return DataTables::of($query)
            ->addColumn('DT_RowIndex', function($data) {
                static $index = 0;
                return ++$index;
            })
            ->addColumn('action', function($data) {
                return $data->approved ? 
                    '<span class="text-success"><i class="fas fa-check"></i> Approved</span>' :
                    '<button class="btn btn-sm btn-approve" data-id="'.$data->id.'">Approve</button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function showInputForm($id)
    {
        $opname = Opname::with(['barang' => function($q) { $q->withTrashed(); }])->findOrFail($id);
        return view('opname.input-lapangan', [
            'title' => 'Input Stok Lapangan',
            'opname' => $opname
        ]);
    }

    public function simpanLapangan($id, Request $request)
    {
        $request->validate([
            'total_lapangan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $opname = Opname::findOrFail($id);
        $opname->update([
            'total_lapangan' => $request->total_lapangan,
            'selisih' => $request->total_lapangan - $opname->stock_total,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('opname.index')->with('success', 'Data lapangan berhasil disimpan!');
    }

    public function approve($id, Request $request)
    {
        DB::transaction(function () use ($id, $request) {
            $opname = Opname::findOrFail($id);
            
            // Update stok
            Stok::where('barang_kode', $opname->barang_kode)
                ->update(['jumlah' => $opname->total_lapangan]);
                
            // Tambahkan approved_by
            $opname->update([
                'approved' => true,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);
        });
        
        return response()->json(['success' => true]);
    }

    public function missIndex(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        return view('opname.miss', [
            'title' => 'Data Miss',
            'currentMonth' => $month
        ]);
    }

    public function dataMiss(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $query = Opname::with(['barang' => function($q) {
            $q->withTrashed();
        }])
            ->where('selisih', '!=', 0) // Filter hanya data dengan selisih tidak sama dengan 0
            ->whereBetween('periode_awal', [$start, $end]) // Filter berdasarkan periode
            ->select('opname.*');

        return DataTables::of($query)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('barang_nama', function ($data) {
                return $data->barang->nama ?? '-';
            })
            ->addColumn('barang_satuan', function ($data) {
                return $data->barang->satuan ?? '-';
            })
            ->make(true);
    }
}
