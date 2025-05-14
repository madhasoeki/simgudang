<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempatStatus extends Model
{
    protected $table = 'tempat_status';
    protected $fillable = [
        'tempat_id', 
        'tahun', 
        'bulan', 
        'status', 
        'total'
    ];

    // Relasi ke tempat (satu status dimiliki oleh satu tempat)
    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'tempat_id');
    }
}
