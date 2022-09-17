<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadkul extends Model
{
    use HasFactory;
    protected $table = 'jadwalkuliah';
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'id_pengampu', 'id_jam', 'id_hari', 'id_ruang'
    ];
}
