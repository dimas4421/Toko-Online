<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoProduk extends Model
{
    use HasFactory;

    protected $table = 'foto_produk';

    protected $guarded = ['id'];

    public $timestamps = true;

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
