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
        Schema::create('barang_miss', function (Blueprint $table) {
            $table->id();
            $table->string('barang_kode', 10);
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->decimal('harga', 15, 2);
            $table->decimal('total', 15, 2);
            
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
        Schema::dropIfExists('barang_miss');
    }
};
