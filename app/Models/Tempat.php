<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tempat extends Model
{
    use SoftDeletes;

    protected $table = 'tempat';
    protected $fillable = ['nama'];

    // Relasi ke tempat_status (satu tempat memiliki banyak status bulanan)
    public function tempatStatus(): HasMany
    {
        return $this->hasMany(TempatStatus::class);
    }

    // Relasi ke transaksi_keluar (satu tempat memiliki banyak transaksi keluar)
    public function transaksiKeluar(): HasMany
    {
        return $this->hasMany(TransaksiKeluar::class);
    }
}
