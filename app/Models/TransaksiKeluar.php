<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiKeluar extends Model
{
    protected $table = 'transaksi_keluar';
    protected $fillable = [
        'barang_kode',
        'projek_id',
        'tanggal',
        'qty',
        'harga',
        'jumlah',
        'keterangan',
        'user_id'
    ];

    // Casting tipe data
    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'decimal:2',
        'jumlah' => 'decimal:2',
    ];

    // Relasi ke barang (satu transaksi dimiliki oleh satu barang)
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_kode', 'kode');
    }

    // Relasi ke projek (satu transaksi dimiliki oleh satu projek)
    public function projek(): BelongsTo
    {
        return $this->belongsTo(Projek::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
