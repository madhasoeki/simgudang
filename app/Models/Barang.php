<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode',
        'nama',
        'satuan'
    ];

    // Relasi ke tabel stok
    public function stok()
    {
        return $this->hasOne(Stok::class, 'barang_kode', 'kode');
    }

    // Relasi ke tabel opname
    public function opnames()
    {
        return $this->hasMany(Opname::class, 'barang_kode', 'kode');
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'barang_kode', 'kode');
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'barang_kode', 'kode');
    }
}