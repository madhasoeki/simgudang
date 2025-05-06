<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Opname;
use Illuminate\Console\Command;

class GenerateOpname extends Command
{
    protected $signature = 'opname:generate {month?}';
    protected $description = 'Generate data opname bulanan';

    public function handle()
    {
        $month = $this->argument('month') ?? now()->format('Y-m');
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            // Cek opname berdasarkan tahun dan bulan dari periode_awal
            $existingOpname = Opname::where('barang_kode', $barang->kode)
                ->whereYear('periode_awal', $start->year)
                ->whereMonth('periode_awal', $start->month)
                ->first();

            $total_masuk = $barang->transaksiMasuk()
                ->whereBetween('tanggal', [$start, $end])
                ->sum('qty');

            $total_keluar = $barang->transaksiKeluar()
                ->whereBetween('tanggal', [$start, $end])
                ->sum('qty');

            $previousMonth = $start->copy()->subMonth();
            $stock_awal = Opname::where('barang_kode', $barang->kode)
                ->whereYear('periode_awal', $previousMonth->year)
                ->whereMonth('periode_awal', $previousMonth->month)
                ->value('total_lapangan') ?? 0;

            if ($existingOpname) {
                $existingOpname->update([
                    'stock_awal' => $stock_awal,
                    'total_masuk' => $total_masuk,
                    'total_keluar' => $total_keluar,
                    'stock_total' => $stock_awal + $total_masuk - $total_keluar,
                    // Pertahankan nilai lapangan dan approval
                    'total_lapangan' => $existingOpname->total_lapangan,
                    'selisih' => $existingOpname->total_lapangan - ($stock_awal + $total_masuk - $total_keluar),
                    'approved' => $existingOpname->approved
                ]);
            } else {
                Opname::create([
                    'barang_kode' => $barang->kode,
                    'periode_awal' => $start,
                    'periode_akhir' => $end,
                    'stock_awal' => $stock_awal,
                    'total_masuk' => $total_masuk,
                    'total_keluar' => $total_keluar,
                    'stock_total' => $stock_awal + $total_masuk - $total_keluar,
                    'total_lapangan' => 0,
                    'selisih' => 0,
                    'approved' => false
                ]);
            }
        }
        $this->info("Data opname bulan {$month} berhasil diupdate!");
    }
}