<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = "pinjaman";
    protected $primarykey = "id";
    protected $fillable = [
        'user_id',
        'nominal',
        'tgl_pinjam',
        'keuntungan',
        'rate_keuntungan',
        'jangka_waktu',
        'tgl_jatuh_tempo',
        'sisa_pinjaman',
        'sisa_keuntungan',
        'nominal_tunggakan',
        'kali_tunggakan',
        'tgl_tunggakan',
        'penggunaan',
        'tgl_lunas',
        'otorisasi_by',
    ];
}
