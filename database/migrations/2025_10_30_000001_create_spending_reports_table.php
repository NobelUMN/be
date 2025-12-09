<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spending_reports', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan');
            $table->integer('jumlah')->nullable();
            $table->string('kategori')->nullable();
            $table->date('tanggal');
            $table->integer('harga');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('spending_reports');
    }
};
