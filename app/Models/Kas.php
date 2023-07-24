<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $table = "kas";
    protected $primarykey = "id";
    protected $fillable = [
        'user_id',
        'kas_awal',
        'kas_masuk',
        'kas_keluar',
        'kas_akhir',
    ];
}
