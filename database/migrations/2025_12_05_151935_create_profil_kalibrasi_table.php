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
        Schema::create('profil_kalibrasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->string('perangkat', 100)->nullable();
            $table->string('status', 20)->nullable()->comment('valid / perlu_ulang');
            $table->float('titik_tengah_x')->nullable();
            $table->float('titik_tengah_y')->nullable();
            $table->float('batas_atas')->nullable();
            $table->float('batas_bawah')->nullable();
            $table->text('catatan')->nullable();
            $table->dateTime('waktu_kalibrasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_kalibrasi');
    }
};
