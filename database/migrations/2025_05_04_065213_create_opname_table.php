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
        Schema::create('opname', function (Blueprint $table) {
            $table->id();
            $table->string('barang_kode', 10);
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->integer('stock_awal');
            $table->integer('total_masuk');
            $table->integer('total_keluar');
            $table->integer('total_lapangan');
            $table->integer('miss');
            $table->decimal('harga', 15, 2);
            
            $table->foreign('barang_kode')
                  ->references('kode')
                  ->on('barang')
                  ->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opname');
    }
};
