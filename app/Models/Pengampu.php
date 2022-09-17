<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengampu extends Model
{
    use HasFactory;
    protected $table = 'pengampu';
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'id_mk', 'id_dosen', 'kelas', 'tahun_akademik'
    ];

    // public function matkul()
    // {
    //     return $this->belongsTo('App\Matkul');
    // }

    // public function dosen()
    // {
    //     return $this->belongsTo('App\Dosen');
    // }

    // public function jadkul()
    // {
    //     return $this->hasMany('App\Jadkul');
    // }
}
