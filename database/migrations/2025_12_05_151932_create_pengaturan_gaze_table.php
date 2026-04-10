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
        Schema::create('pengaturan_gaze', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->unique()->constrained('pengguna')->onDelete('cascade');
            $table->integer('sensitivitas_atas')->nullable();
            $table->integer('sensitivitas_bawah')->nullable();
            $table->integer('dwell_time_ms')->nullable();
            $table->integer('kecepatan_scroll')->nullable();
            $table->boolean('suara_diaktifkan')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_gaze');
    }
};
