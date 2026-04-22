<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->nullable()->constrained('materi')->nullOnDelete();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->foreignId('dibuat_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('kuis_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kuis_id');
            $table->text('pertanyaan');
            $table->unsignedInteger('urutan')->default(1);
            $table->timestamps();

            $table->foreign('kuis_id')->references('id')->on('kuis')->cascadeOnDelete();
        });

        Schema::create('kuis_opsi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pertanyaan_id');
            $table->string('label', 5);
            $table->text('teks');
            $table->boolean('benar')->default(false);
            $table->timestamps();

            $table->foreign('pertanyaan_id')->references('id')->on('kuis_pertanyaan')->cascadeOnDelete();
        });

        Schema::create('kuis_hasil', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kuis_id');
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->unsignedInteger('skor')->default(0);
            $table->unsignedInteger('total_benar')->default(0);
            $table->unsignedInteger('total_pertanyaan')->default(0);
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();

            $table->foreign('kuis_id')->references('id')->on('kuis')->cascadeOnDelete();
        });

        Schema::create('kuis_jawaban', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kuis_hasil_id');
            $table->unsignedBigInteger('pertanyaan_id');
            $table->unsignedBigInteger('opsi_id')->nullable();
            $table->boolean('benar')->default(false);
            $table->timestamps();

            $table->foreign('kuis_hasil_id')->references('id')->on('kuis_hasil')->cascadeOnDelete();
            $table->foreign('pertanyaan_id')->references('id')->on('kuis_pertanyaan')->cascadeOnDelete();
            $table->foreign('opsi_id')->references('id')->on('kuis_opsi')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_jawaban');
        Schema::dropIfExists('kuis_hasil');
        Schema::dropIfExists('kuis_opsi');
        Schema::dropIfExists('kuis_pertanyaan');
        Schema::dropIfExists('kuis');
    }
};
