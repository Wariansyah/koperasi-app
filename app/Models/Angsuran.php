<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = "angsuran";
    protected $primarykey = "id";
    protected $fillable = [
        'user_id',
        'rekening_pinjaman',
        'angsuran_ke',
        'nominal_pokok',
        'nominal_untung',
        'jadwal_ansur',
        'tgl_bayar',
        'nominal_byr_pokok',
        'nominal_byr_untung',
    ];
}
