<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Nama tabel eksplisit (karena di DB bernama 'user' bukan 'users')
    protected $table = 'user';

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'role',
        'status',
        'password',
        'hp',
        'foto',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
