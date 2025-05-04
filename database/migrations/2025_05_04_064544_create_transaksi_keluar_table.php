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
        Schema::create('transaksi_keluar', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('barang_kode', 10);
            $table->foreignId('projek_id');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
    
            $table->foreign('barang_kode')
                  ->references('kode')
                  ->on('barang')
                  ->onDelete('cascade');
    
            $table->foreign('projek_id')
                  ->references('id')
                  ->on('projek')
                  ->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluar');
    }
};
