<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_bab', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('materi_id');
            $table->string('judul_bab', 200);
            $table->unsignedInteger('urutan')->default(1);
            $table->string('tipe_konten', 20)->comment('teks / file');
            $table->longText('konten_teks')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->string('pdf_page_selection')->nullable();
            $table->unsignedInteger('jumlah_halaman')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->index(['materi_id', 'urutan']);
            $table->foreign('materi_id')->references('id')->on('materi')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_bab');
    }
};
