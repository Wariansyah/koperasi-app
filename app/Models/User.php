<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $primaryKey = 'id'; // Nama kolom ID (asumsi menggunakan 'id')
    public $incrementing = false; // Kolom ID bukan auto-increment
    protected $keyType = 'string';
    protected $fillable = [
        'id', // Pastikan 'id' ada dalam daftar fillable
        'name',
        'email',
        'password',
        'no_induk',
        'alamat',
        'telepon',
        'status',
        'jenkel',
        'tgl_lahir',
        'tmpt_lahir',
        'limit_pinjaman',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id'); // Gunakan 'role_id' sebagai foreign key
    }

    // Generate 16-character unique ID before saving the User
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (!$model->id) {
                $model->id = Str::random(16);
            }
        });
    }
}
