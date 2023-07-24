<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    protected $table = "pinjaman";
    protected $primarykey = "id";
    protected $fillable = [
        'rekening_simpanan',
        'user_id',
        'tgl_buka',
        'tgl_tutup',
        'nominal',
        'keterangan',
    ];
}
