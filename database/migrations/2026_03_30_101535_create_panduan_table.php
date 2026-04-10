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
    Schema::create('panduan', function (Blueprint $table) {
        $table->id();

        // isi panduan
        $table->string('judul');        // judul langkah (contoh: Pilih Materi)
        $table->text('deskripsi');      // isi penjelasan

        // tambahan biar fleksibel
        $table->string('tag')->nullable();   // contoh: 1, 2, Voice Nav
        $table->integer('urutan')->default(0); // biar bisa diurutin

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panduan');
    }
};
