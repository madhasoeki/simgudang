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
        Schema::create('projek', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100); // AL-AKRAM, MASJID, dll
            $table->enum('status', ['DONE', 'LOADING'])->default('LOADING');
            $table->decimal('total', 15, 2)->default(0); // Total pengeluaran proyek
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projek');
    }
};
