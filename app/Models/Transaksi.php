<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = "pinjaman";
    protected $primarykey = "id";
    protected $fillable = [
        'jurnal_id',
        'tanggal',
        'jam',
        'rekening_pinjaman',
        'ledger',
        'keterangan',
        'nominal_debet',
        'nominal_kredit',
    ];
}
