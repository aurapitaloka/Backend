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
        Schema::create('log_perintah_suara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_id')->constrained('sesi_baca')->onDelete('cascade');
            $table->string('jenis_perintah', 30)->nullable()->comment('SCROLL_UP, SCROLL_DOWN, NEXT_PAGE, PAUSE');
            $table->string('teks_terdeteksi', 255)->nullable();
            $table->boolean('berhasil')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_perintah_suara');
    }
};
