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
        'rekening',
        'no_induk',
        'tgl_buka',
        'tgl_tutup',
        'nominal',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
