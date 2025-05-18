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
                return $this->formatHistoryValues($row->table_name, $row->old_values);
            })
            ->editColumn('new_values', function($row) {
                return $this->formatHistoryValues($row->table_name, $row->new_values);
            })
            ->rawColumns(['old_values', 'new_values'])
            ->make(true);

    }

    /**
     * Format history values for display in the table.
     */
    private function formatHistoryValues($table, $values)
    {
        if (empty($values) || !is_array($values)) return '-';

        // Remove unwanted fields
        unset($values['created_at'], $values['updated_at'], $values['id']);

        $result = [];
        switch ($table) {
            case 'barang':
                $fields = ['kode', 'nama', 'satuan'];
                foreach ($fields as $f) {
                    if (isset($values[$f])) $result[$f] = $values[$f];
                }
                break;
            case 'tempat':
                if (isset($values['nama'])) $result['nama'] = $values['nama'];
                break;
            case 'transaksi_masuk':
                $result['barang_kode'] = $values['barang_kode'] ?? '';
                $result['barang_nama'] = $values['barang_nama'] ?? ($values['nama'] ?? '');
                $result['qty'] = $values['qty'] ?? '';
                $result['harga'] = $this->formatRupiah($values['harga'] ?? '');
                $jumlah = $values['jumlah'] ?? ((isset($values['qty'], $values['harga'])) ? $values['qty'] * $values['harga'] : '');
                $result['jumlah'] = $this->formatRupiah($jumlah);
                break;
            case 'transaksi_keluar':
                $result['barang_kode'] = $values['barang_kode'] ?? '';
                $result['barang_nama'] = $values['barang_nama'] ?? ($values['nama'] ?? '');
                $result['qty'] = $values['qty'] ?? '';
                $result['harga'] = $this->formatRupiah($values['harga'] ?? '');
                $jumlah = $values['jumlah'] ?? ((isset($values['qty'], $values['harga'])) ? $values['qty'] * $values['harga'] : '');
                $result['jumlah'] = $this->formatRupiah($jumlah);
                $result['keterangan'] = $values['keterangan'] ?? '';
                $result['tempat_nama'] = $values['tempat_nama'] ?? ($values['tempat']['nama'] ?? '');
                break;
            case 'opname':
                // Format periode: 26 Apr 2025 - 25 May 2025
                if (isset($values['periode_awal'], $values['periode_akhir'])) {
                    $result['periode'] = $this->formatPeriode($values['periode_awal'], $values['periode_akhir']);
                } else {
                    $result['periode'] = '';
                }
                $result['barang_kode'] = $values['barang_kode'] ?? '';
                $result['barang_nama'] = $values['barang_nama'] ?? ($values['nama'] ?? '');
                $result['total_lapangan'] = $values['total_lapangan'] ?? '';
                $result['keterangan'] = $values['keterangan'] ?? '';
                break;
            default:
                $result = $values;
        }
        if (empty($result)) return '-';
        $str = '';
        foreach ($result as $key => $val) {
            $str .= '<b>' . ucfirst(str_replace('_', ' ', $key)) . '</b>: ' . (is_array($val) ? json_encode($val) : $val) . '<br>';
        }
        return $str;
    }

    /**
     * Format number to Rupiah (Rp50.000)
     */
    private function formatRupiah($value)
    {
        if ($value === '' || $value === null) return '';
        $number = is_numeric($value) ? $value : floatval(preg_replace('/[^\d.]/', '', $value));
        return 'Rp' . number_format($number, 0, ',', '.');
    }

    /**
     * Format periode to "26 Apr 2025 - 25 May 2025"
     */
    private function formatPeriode($awal, $akhir)
    {
        try {
            $start = \Carbon\Carbon::parse($awal);
            $end = \Carbon\Carbon::parse($akhir);
            $startStr = $start->format('d M Y');
            $endStr = $end->format('d M Y');
            return $startStr . ' - ' . $endStr;
        } catch (\Exception $e) {
            return $awal . ' - ' . $akhir;
        }
    }
}
