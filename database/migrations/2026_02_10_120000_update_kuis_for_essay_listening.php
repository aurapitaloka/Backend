<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuis_pertanyaan', function (Blueprint $table) {
            $table->string('tipe', 20)->default('pilihan');
            $table->text('jawaban_teks')->nullable();
            $table->string('keyword', 255)->nullable();
            $table->string('audio_path', 255)->nullable();
            $table->text('audio_text')->nullable();
            $table->string('bahasa', 10)->nullable();
        });

        Schema::table('kuis_jawaban', function (Blueprint $table) {
            $table->text('jawaban_teks')->nullable();
            $table->unsignedInteger('skor_auto')->nullable();
            $table->string('status_koreksi', 20)->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('kuis_jawaban', function (Blueprint $table) {
            $table->dropColumn(['jawaban_teks', 'skor_auto', 'status_koreksi']);
        });

        Schema::table('kuis_pertanyaan', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'jawaban_teks', 'keyword', 'audio_path', 'audio_text', 'bahasa']);
        });
    }
};
