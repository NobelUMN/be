<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_spending', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('spending_report_id')->unsigned();
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->string('supplier')->nullable();
            $table->string('catatan')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('spending_report_id')
                ->references('id')
                ->on('spending_reports')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_spending');
    }
};
