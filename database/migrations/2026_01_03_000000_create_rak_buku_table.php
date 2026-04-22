<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rak_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('materi_id')->constrained('materi')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['pengguna_id','materi_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rak_buku');
    }
};
