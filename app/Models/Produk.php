<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $guarded = ['id'];

    public $timestamps = true;

    /**
     * Relasi ke tabel kategori (banyak produk dimiliki satu kategori)
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi ke tabel user (produk dibuat oleh satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke foto produk (satu produk punya banyak foto tambahan)
     */
    public function fotoProduk()
    {
        return $this->hasMany(FotoProduk::class);
    }
}
