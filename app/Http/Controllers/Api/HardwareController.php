<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Produk;

class HardwareController extends Controller
{
    // ESP32 mengirim barcode
    public function barcode(Request $request)
    {
        $barcode = $request->barcode;
        $mode = $request->mode;

        if (!$barcode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode missing'
            ], 400);
        }

        // Cek apakah barcode ada di database
        $produk = Produk::where('barcode', $barcode)->first();

        if (!$produk) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode not found in database',
                'barcode' => $barcode,
                'mode' => $mode
            ], 404);
        }

        // Simpan sementara, auto-expire 30 detik
        Cache::put('last_scanned_barcode', $barcode, 30);
        
        // Simpan info produk juga
        Cache::put('last_scanned_produk', $produk, 30);

        return response()->json([
            'status' => 'success',
            'barcode' => $barcode,
            'mode' => $mode,
            'produk' => [
                'id' => $produk->id_produk,
                'nama' => $produk->nama_produk,
                'harga' => $produk->harga,
                'stok' => $produk->stok ?? null
            ]
        ]);
    }

    // Frontend mengambil barcode
    public function getBarcode()
    {
        // pull = ambil & hapus → sehingga tidak stay di backend
        $barcode = Cache::pull('last_scanned_barcode');
        $produk = Cache::pull('last_scanned_produk');

        return response()->json([
            'barcode' => $barcode,
            'produk' => $produk
        ]);
    }
}