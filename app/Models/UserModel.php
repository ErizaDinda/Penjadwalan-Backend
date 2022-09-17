<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
    protected $table = 'user_model';
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'username', 'pass', 'level'
    ];
}
