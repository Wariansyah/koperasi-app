<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $table = "ledger";
    protected $primarykey = "id";
    protected $fillable = [
        'kode',
        'name',
        'keterangan',
    ];
}
