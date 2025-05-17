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
        $histories = History::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $title = 'History';
        $isSuperAdmin = auth()->user() && auth()->user()->hasRole('super-admin');

        // Ambil mapping id => nama tempat
        $tempatMap = Tempat::pluck('nama', 'id')->toArray();

        return view('history.index', compact('histories', 'title', 'isSuperAdmin', 'tempatMap'));
    }
}
