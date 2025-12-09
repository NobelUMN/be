<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSpending extends Model
{
    use HasFactory;


    protected $table = 'detail_spending';

    protected $fillable = [
        'spending_report_id', 'nama_barang', 'jumlah', 'harga', 'supplier', 'catatan'
    ];

    public function spendingReport()
    {
        return $this->belongsTo(SpendingReport::class, 'spending_report_id');
    }

    // Hapus relasi hasMany detailSpending, cukup relasi belongsTo spendingReport
}
