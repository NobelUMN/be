<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{

    public function index()
    {
        return response()->json(Produk::all());
    }


    public function show(string $id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
        return response()->json($produk);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string',
            'harga_modal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori' => 'required|string',
            'lokasi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'id_barcode' => 'nullable|string|max:100',
        ]);
        $produk = Produk::create($validated);
        return response()->json($produk, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string',
            'harga_modal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori' => 'required|string',
            'lokasi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'id_barcode' => 'nullable|string|max:100',
        ]);

        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
        $produk->update($validated);
        return response()->json($produk);
    }


    public function destroy($id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
        $produk->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }

    public function create()
{
    $barcode = session('last_scanned_barcode');

    return view('produk.create', compact('barcode'));
}

    // Cari produk berdasarkan id_barcode
    public function byBarcode(string $code)
    {
        // Coba cari berdasarkan id_barcode atau id_produk (beberapa DB menyimpan sebagai numeric)
        $produk = Produk::where('id_barcode', $code)
            ->orWhere('id_produk', $code)
            ->first();
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
        return response()->json($produk);
    }
}
