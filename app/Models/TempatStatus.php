<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempatStatus extends Model
{

    protected $table = 'tempat_status';
    protected $fillable = [
        'tempat_id',
        'periode_awal',
        'periode_akhir',
        'status',
        'total'
    ];

    // Relasi ke tempat (satu status dimiliki oleh satu tempat)
    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'tempat_id');
    }

    // Scope untuk filter berdasarkan periode
    public function scopeInPeriode($query, $start, $end)
    {
        return $query->where('periode_awal', $start)->where('periode_akhir', $end);
    }
}
