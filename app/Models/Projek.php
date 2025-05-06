<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Projek extends Model
{
    protected $table = 'projek';
    protected $fillable = ['nama'];

    // Relasi ke projek_status (satu projek memiliki banyak status bulanan)
    public function projekStatus(): HasMany
    {
        return $this->hasMany(ProjekStatus::class);
    }

    // Relasi ke transaksi_keluar (satu projek memiliki banyak transaksi keluar)
    public function transaksiKeluar(): HasMany
    {
        return $this->hasMany(TransaksiKeluar::class);
    }
}
