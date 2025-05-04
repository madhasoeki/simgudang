<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projek extends Model
{
     // Table configuration
     protected $table = 'projek';
     
     // Mass assignment protection
     protected $fillable = ['nama', 'status', 'total'];
}
