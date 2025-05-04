<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    protected $table = 'transaksi_masuk';

    // Mass assignment protection
    protected $fillable = ['tanggal', 'barang_kode', 'qty', 'harga', 'jumlah'];

    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'decimal:2',
        'jumlah' => 'decimal:2',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_kode');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            // Auto calculate jumlah jika belum diisi
            if (!$model->jumlah) {
                $model->jumlah = $model->qty * $model->harga;
            }
        });
    }
}
