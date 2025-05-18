<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Tempat;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super-admin']);
    }

    public function index(Request $request)
    {
        $title = 'History';
        $isSuperAdmin = auth()->user() && auth()->user()->hasRole('super-admin');
        $tempatMap = Tempat::pluck('nama', 'id')->toArray();
        return view('history.index', compact('title', 'isSuperAdmin', 'tempatMap'));
    }

    public function data(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $query = History::with('user');
        if ($start && $end) {
            $query->whereBetween('created_at', [
                $start . ' 00:00:00',
                $end . ' 23:59:59'
            ]);
        }
        $histories = $query->orderBy('created_at', 'desc');

        // DataTables server-side
        return datatables()->of($histories)
            ->addIndexColumn()
            ->editColumn('tanggal', function($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y') : '';
            })
            ->editColumn('jam', function($row) {
                return $row->created_at ? $row->created_at->format('H:i:s') : '';
            })
            ->editColumn('user', function($row) {
                return $row->user ? $row->user->name : '-';
            })
            ->editColumn('old_values', function($row) {
                $ov = $row->old_values ?? [];
                if (!empty($ov) && is_array($ov)) {
                    $str = '';
                    foreach ($ov as $key => $val) {
                        $str .= '<b>' . ucfirst($key) . '</b>: ' . (is_array($val) ? json_encode($val) : $val) . '<br>';
                    }
                    return $str;
                }
                return '-';
            })
            ->editColumn('new_values', function($row) {
                $nv = $row->new_values ?? [];
                if (!empty($nv) && is_array($nv)) {
                    $str = '';
                    foreach ($nv as $key => $val) {
                        $str .= '<b>' . ucfirst($key) . '</b>: ' . (is_array($val) ? json_encode($val) : $val) . '<br>';
                    }
                    return $str;
                }
                return '-';
            })
            ->rawColumns(['old_values', 'new_values'])
            ->make(true);
    }
}
