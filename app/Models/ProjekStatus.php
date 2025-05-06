<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjekStatus extends Model
{
    protected $table = 'projek_status';
    protected $fillable = [
        'projek_id', 
        'tahun', 
        'bulan', 
        'status', 
        'total'
    ];

    // Relasi ke projek (satu status dimiliki oleh satu projek)
    public function projek()
    {
        return $this->belongsTo(Projek::class, 'projek_id');
    }
}
