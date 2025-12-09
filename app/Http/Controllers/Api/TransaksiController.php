<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    // Update status transaksi
    public function updateStatus(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }
        $validated = $request->validate([
            'status' => 'required|string',
        ]);
        $transaksi->status = $validated['status'];
        $transaksi->save();
        return response()->json(['status' => $transaksi->status]);
    }

    public function index()
    {
        $data = Transaksi::with('produk')->get();
        return response()->json($data);
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('details.produk')->find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }
        return response()->json($transaksi);
    }

    public function store(Request $request)
    {
        \Log::info('Transaksi request:', $request->all());

        try {
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'nama_pelanggan' => 'required|string',
                'nama_kasir' => 'required|string',
                'total_pembayaran' => 'required|numeric',
                'nominal_bayar' => 'required|numeric',
                'metode_pembayaran' => 'required|string',
                'status' => 'required|string',
                'items' => 'required|array|min:1',
                'items.*.id_produk' => 'required|integer|exists:produk,id_produk',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.harga' => 'required|numeric',
                'items.*.subtotal' => 'required|numeric',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $transaksi = Transaksi::create([
                'tanggal' => $validated['tanggal'],
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'nama_kasir' => $validated['nama_kasir'],
                'total_pembayaran' => $validated['total_pembayaran'],
                'nominal_bayar' => $validated['nominal_bayar'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status' => $validated['status'],
            ]);

            foreach ($validated['items'] as $item) {
                $transaksi->details()->create([
                    'id_produk' => $item['id_produk'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                ]);
                // Kurangi stok produk
                $produk = \App\Models\Produk::find($item['id_produk']);
                if ($produk) {
                    $produk->decrement('stok', $item['qty']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'data' => $transaksi->load('details')
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Transaksi error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }
        $transaksi->update($request->only([
            'id_produk', 'tanggal', 'nama_pelanggan', 'nama_kasir'
        ]));
        return response()->json($transaksi);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }
        $transaksi->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }

}
