<?php

namespace App\Http\Controllers;

use App\Models\Tempat;
use App\Models\TempatStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RekapController extends Controller
{
    public function index()
    {
        return view('rekap-barang-keluar', [
            'title' => 'Rekap Status Tempat',
        ]);
    }
    public function data(Request $request)
    {
        $query = TempatStatus::join('tempat', 'tempat_status.tempat_id', '=', 'tempat.id')
            ->select('tempat_status.*', 'tempat.nama');

        // Filter berdasarkan bulan dan tahun
        if ($request->has('start_date') && $request->start_date) {
            $month = date('n', strtotime($request->start_date)); // Ambil bulan (1-12)
            $year = date('Y', strtotime($request->start_date)); // Ambil tahun
            
            $query->where([
                'tempat_status.bulan' => $month,
                'tempat_status.tahun' => $year
            ]);
        }

        return DataTables::of($query)
            ->addColumn('DT_RowIndex', function($data) {
                static $index = 0;
                return ++$index;
            })
            ->filter(function ($query) use ($request) {
                // Handle server-side search
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where('tempat.nama', 'like', "%{$search}%");
                }
            })
            ->toJson();
    }

    public function updateStatus($id, Request $request)
    {
        try {
            $status = TempatStatus::findOrFail($id);
            $status->update([
                'status' => $request->status
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
