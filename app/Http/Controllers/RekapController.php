<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Models\ProjekStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RekapController extends Controller
{
    public function createProjek()
    {
        return view('projek.create');
    }

    public function storeProjek(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:projek,nama|max:255'
        ]);

        Projek::create([
            'nama' => $request->nama
        ]);

        return redirect('/rekap-projek')->with('success', 'Projek berhasil ditambahkan!');
    }

    public function data(Request $request)
    {
        $query = ProjekStatus::join('projek', 'projek_status.projek_id', '=', 'projek.id')
            ->select('projek_status.*', 'projek.nama');

        // Filter berdasarkan bulan dan tahun
        if ($request->has('start_date') && $request->start_date) {
            $month = date('n', strtotime($request->start_date)); // Ambil bulan (1-12)
            $year = date('Y', strtotime($request->start_date)); // Ambil tahun
            
            $query->where([
                'projek_status.bulan' => $month,
                'projek_status.tahun' => $year
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
                    $query->where('projek.nama', 'like', "%{$search}%");
                }
            })
            ->toJson();
    }

    public function updateStatus($id, Request $request)
    {
        try {
            $status = ProjekStatus::findOrFail($id);
            $status->update([
                'status' => $request->status
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
