<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

        // Simpan sementara, auto-expire 30 detik
        Cache::put('last_scanned_barcode', $barcode, 30);

        return response()->json([
            'status' => 'success',
            'barcode' => $barcode,
            'mode' => $mode,
        ]);
    }

    // Frontend mengambil barcode
    public function getBarcode()
    {
        // pull = ambil & hapus → sehingga tidak stay di backend
        $barcode = Cache::pull('last_scanned_barcode');

        return response()->json([
            'barcode' => $barcode
        ]);
    }
}