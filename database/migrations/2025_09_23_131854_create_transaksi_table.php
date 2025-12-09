<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi');
            $table->dateTime('tanggal');
            $table->string('nama_pelanggan');
            $table->string('nama_kasir');
            $table->decimal('total_pembayaran', 15, 2);
            $table->decimal('nominal_bayar', 15, 2);
            $table->string('metode_pembayaran');
            $table->string('status');
            $table->timestamps();
        });

        // ...existing code...
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
        Schema::dropIfExists('transaksi');
    }

};
