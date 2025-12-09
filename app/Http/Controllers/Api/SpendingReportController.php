<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SpendingReport;

class SpendingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SpendingReport::with('detailSpending')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'keterangan' => 'required|string',
            'kategori' => 'nullable|string',
            'tanggal' => 'required|date',
            'harga' => 'required|integer',
            'details' => 'required|array|min:1',
            'details.*.nama_barang' => 'required|string',
            'details.*.jumlah' => 'nullable|integer',
            'details.*.harga' => 'nullable|integer',
            'details.*.supplier' => 'nullable|string',
            'details.*.catatan' => 'nullable|string',
        ]);

        $spending = SpendingReport::create([
            'keterangan' => $data['keterangan'],
            'kategori' => $data['kategori'] ?? null,
            'tanggal' => $data['tanggal'],
            'harga' => $data['harga'],
        ]);

        foreach ($data['details'] as $detail) {
            $spending->detailSpending()->create($detail);
        }

        return response()->json($spending->load('detailSpending'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $spending = SpendingReport::with('detailSpending')->find($id);
        if (!$spending) {
            return response()->json(['message' => 'Spending report tidak ditemukan'], 404);
        }
        return response()->json($spending);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $spending = SpendingReport::find($id);
        if (!$spending) {
            return response()->json(['message' => 'Spending report tidak ditemukan'], 404);
        }
        
        $data = $request->validate([
            'keterangan' => 'required|string',
            'kategori' => 'nullable|string',
            'tanggal' => 'required|date',
            'harga' => 'required|integer',
        ]);
        
        $spending->update($data);
        return response()->json($spending->load('detailSpending'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $spending = SpendingReport::find($id);
        if (!$spending) {
            return response()->json(['message' => 'Spending report tidak ditemukan'], 404);
        }
        $spending->detailSpending()->delete();
        $spending->delete();
        return response()->json(['message' => 'Spending report berhasil dihapus']);
    }
}
