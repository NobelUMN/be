<?php
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\TransaksiDetailController;
use App\Http\Controllers\Api\SpendingReportController;
use App\Http\Controllers\Api\DetailSpendingController;
use App\Http\Controllers\Api\HardwareController;

// ============ PUBLIC ROUTES (tanpa auth) ============
// CORS preflight
Route::options('/login', function() { return response()->noContent(); });

Route::post('/login', [LoginController::class, 'login']);

// GET produk (public - semua bisa baca)
Route::get('produk', [ProdukController::class, 'index']);
Route::get('produk/{produk}', [ProdukController::class, 'show']);
Route::get('produk/barcode/{code}', [ProdukController::class, 'byBarcode']);

// Transaksi - PUBLIC (tanpa auth)
Route::get('transaksi', [TransaksiController::class, 'index']);
Route::get('transaksi/{id}', [TransaksiController::class, 'show']);
Route::post('transaksi', [TransaksiController::class, 'store']);
Route::put('transaksi/{id}', [TransaksiController::class, 'update']);
Route::delete('transaksi/{id}', [TransaksiController::class, 'destroy']);
Route::patch('transaksi/{id}/status', [TransaksiController::class, 'updateStatus']);

// Transaksi Detail - PUBLIC
Route::get('transaksi_detail', [TransaksiDetailController::class, 'index']);
Route::get('transaksi_detail/{id}', [TransaksiDetailController::class, 'show']);
Route::post('transaksi_detail', [TransaksiDetailController::class, 'store']);
Route::put('transaksi_detail/{id}', [TransaksiDetailController::class, 'update']);
Route::delete('transaksi_detail/{id}', [TransaksiDetailController::class, 'destroy']);

// Hardware webhook (public)
Route::post('hardware/webhook', [HardwareController::class, 'receive']);
Route::get('hardware/webhook', function(){ return response('OK - use POST for webhook', 200); });
Route::post('hardware/barcode', [HardwareController::class, 'barcode']);
Route::get('hardware/barcode', function () {
    $barcode = Cache::pull('last_scanned_barcode'); 
    return response()->json(['barcode' => $barcode]);
});

// ============ ADMIN ONLY ROUTES (auth:sanctum + role:admin) ============
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // User management - admin only
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    
    // Produk management - admin only (create, update, delete)
    Route::post('produk', [ProdukController::class, 'store']);
    Route::put('produk/{id}', [ProdukController::class, 'update']);
    Route::delete('produk/{id}', [ProdukController::class, 'destroy']);
    
    // Spending report - admin only
    Route::get('spending-report', [SpendingReportController::class, 'index']);
    Route::post('spending-report', [SpendingReportController::class, 'store']);
    Route::get('spending-report/{id}', [SpendingReportController::class, 'show']);
    Route::put('spending-report/{id}', [SpendingReportController::class, 'update']);
    Route::delete('spending-report/{id}', [SpendingReportController::class, 'destroy']);
    
    Route::get('detail-spending', [DetailSpendingController::class, 'index']);
    Route::post('detail-spending', [DetailSpendingController::class, 'store']);
    Route::get('detail-spending/{id}', [DetailSpendingController::class, 'show']);
    Route::put('detail-spending/{id}', [DetailSpendingController::class, 'update']);
    Route::delete('detail-spending/{id}', [DetailSpendingController::class, 'destroy']);
});

// ============ AUTHENTICATED ROUTES (auth:sanctum) ============
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout']);
    
    // Hardware command - all authenticated users
    Route::post('hardware/command', [HardwareController::class, 'command']);
});