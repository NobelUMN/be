<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailSpending;


class DetailSpendingController extends Controller
{
    public function index()
    {
        // Ambil semua detail spending beserta relasi spendingReport
        $data = DetailSpending::with('spendingReport')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spending_report_id' => 'required|exists:spending_reports,id',
            'nama_barang' => 'required|string',
            'jumlah' => 'nullable|integer',
            'harga' => 'nullable|integer',
            'supplier' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);
        
        $detail = DetailSpending::create($validated);
        return response()->json($detail, 201);
    }

    public function show($id)
    {
        $detail = DetailSpending::with('spendingReport')->find($id);
        if (!$detail) {
            return response()->json(['message' => 'Detail spending tidak ditemukan'], 404);
        }
        return response()->json($detail);
    }

    public function update(Request $request, $id)
    {
        $detail = DetailSpending::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Detail spending tidak ditemukan'], 404);
        }
        $validated = $request->validate([
            'nama_barang' => 'required|string',
            'jumlah' => 'nullable|integer',
            'harga' => 'nullable|integer',
            'supplier' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);
        $detail->update($validated);
        return response()->json($detail, 200);
    }

    public function destroy($id)
    {
        $detail = DetailSpending::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Detail spending tidak ditemukan'], 404);
        }
        $detail->delete();
        return response()->json(['message' => 'Detail spending berhasil dihapus']);
    }
}
