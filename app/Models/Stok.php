<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model {
    protected $table = 'stok';

    protected $primaryKey = 'barang_kode';
    public $incrementing = false;
    protected $fillable = ['barang_kode', 'jumlah'];
}