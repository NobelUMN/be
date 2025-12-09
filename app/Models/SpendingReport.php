<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SpendingReport extends Model
{
    protected $fillable = [
        'keterangan', 'jumlah', 'kategori', 'tanggal', 'harga'
    ];

    public function store(Request $request)
    {
        $data = $request->validate([
            'keterangan' => 'required|string',
            'jumlah' => 'nullable|integer',
            'kategori' => 'nullable|string',
            'tanggal' => 'required|date',
            'harga' => 'required|integer',
        ]);
        $spending = SpendingReport::create($data);
        return response()->json($spending, 201);
    }

public function detailSpending()
{
    return $this->hasMany(DetailSpending::class, 'spending_report_id');
}
}
