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
        Schema::create('projek_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projek_id')->constrained('projek');
            $table->year('tahun');
            $table->tinyInteger('bulan'); // 1-12
            $table->enum('status', ['loading', 'done'])->default('loading');
            $table->integer('total')->default(0);
            $table->timestamps();
            $table->unique(['projek_id', 'tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projek_status');
    }
};
