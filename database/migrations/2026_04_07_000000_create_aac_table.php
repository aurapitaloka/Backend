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
        Schema::create('aac', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('judul', 150);
            $table->string('kategori', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar_path', 255)->nullable();
            $table->integer('urutan')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->bigInteger('dibuat_oleh');
            $table->foreign('dibuat_oleh')
                ->references('id')
                ->on('pengguna')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aac');
    }
};
