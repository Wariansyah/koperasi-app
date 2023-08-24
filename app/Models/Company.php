<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = "company";
    protected $primarykey = "id";
    protected $fillable = [
        'nama',
        'alamat',
        'email',
        'telepon',
        'logo',
        'created_by',
        'updated_by',
    ];
}
