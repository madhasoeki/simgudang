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
        Schema::create('tempat_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tempat_id')->constrained('tempat');
            $table->year('tahun');
            $table->tinyInteger('bulan'); // 1-12
            $table->enum('status', ['loading', 'done'])->default('loading');
            $table->integer('total')->default(0);
            $table->timestamps();
            $table->unique(['tempat_id', 'tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat_status');
    }
};
