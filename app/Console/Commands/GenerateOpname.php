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

        // Ambil semua barang yang aktif
        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            // Cek apakah opname untuk barang ini di bulan ini sudah ada
            $existingOpname = Opname::where([
                'barang_kode' => $barang->kode,
                'periode_awal' => $start,
                'periode_akhir' => $end
            ])->exists();

            if (!$existingOpname) {
                $total_masuk = $barang->transaksiMasuk()
                    ->whereBetween('tanggal', [$start, $end])
                    ->sum('qty');

                $total_keluar = $barang->transaksiKeluar()
                    ->whereBetween('tanggal', [$start, $end])
                    ->sum('qty');

                // Ambil stok awal dari bulan sebelumnya
                $previousMonth = $start->copy()->subMonth();
                $stock_awal = Opname::where('barang_kode', $barang->kode)
                    ->where('periode_awal', $previousMonth->startOfMonth())
                    ->value('total_lapangan') ?? 0;

                Opname::updateOrCreate(
                    [
                        'barang_kode' => $barang->kode,
                        'periode_awal' => $start,
                        'periode_akhir' => $end
                    ],
                    [
                        'stock_awal' => $stock_awal,
                        'total_masuk' => $total_masuk,
                        'total_keluar' => $total_keluar,
                        'stock_total' => $stock_awal + $total_masuk - $total_keluar,
                        'total_lapangan' => 0,
                        'selisih' => 0,
                        'approved' => false
                    ]
                );
            }
        }

        $this->info("Data opname bulan {$month} berhasil digenerate!");
    }
}