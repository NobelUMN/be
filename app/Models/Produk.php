<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'id_barcode',
        'nama_produk',
        'harga_modal',
        'harga_jual',
        'kategori',
        'lokasi',
        'stok', 
    ];
}
