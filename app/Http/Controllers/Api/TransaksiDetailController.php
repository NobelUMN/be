<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

class TransaksiDetailController extends Controller
{
    public function index()
    {
        return response()->json(TransaksiDetail::with('produk')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_transaksi' => 'required|exists:transaksi,id_transaksi',
            'id_produk' => 'required|exists:produk,id_produk',
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);

        $detail = TransaksiDetail::create($validated);
        return response()->json($detail, 201);
    }

    public function show($id)
    {
        $detail = TransaksiDetail::with('produk')->find($id);
        if (!$detail) {
            return response()->json(['message' => 'Detail transaksi tidak ditemukan'], 404);
        }
        return response()->json($detail);
    }

    public function update(Request $request, $id)
    {
        $detail = TransaksiDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Detail transaksi tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);

        $detail->update($validated);
        return response()->json($detail);
    }

    public function destroy($id)
    {
        $detail = TransaksiDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Detail transaksi tidak ditemukan'], 404);
        }
        $detail->delete();
        return response()->json(['message' => 'Detail transaksi berhasil dihapus']);
    }
}
