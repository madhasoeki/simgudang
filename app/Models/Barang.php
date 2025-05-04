<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Barang extends Model 
{
    // Table configuration
    protected $table = 'barang';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Mass assignment protection
    protected $fillable = ['kode', 'nama', 'satuan'];

    // Relationship definition (correct placement inside class)
    public function stok(): HasOne
    {
        return $this->hasOne(Stok::class, 'barang_kode', 'kode');
    }
}