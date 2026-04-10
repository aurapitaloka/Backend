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
        Schema::create('fiksi', function (Blueprint $table) {
            $table->id();
            $table->string('judul_buku', 200);
            $table->string('penulis', 150);
            $table->string('kategori', 100)->nullable();
            $table->integer('tahun_terbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->foreignId('dibuat_oleh')->constrained('pengguna')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiksi');
    }
};
