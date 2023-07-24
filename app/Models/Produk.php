<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = "pinjaman";
    protected $primarykey = "id";
    protected $fillable = [
        'kode',
        'ledger',
        'keterangan',
    ];
}
