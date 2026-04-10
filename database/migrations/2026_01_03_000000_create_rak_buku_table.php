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
            // `pengguna.id` and `materi.id` in this project are signed BIGINT (not unsigned),
            // so use signed bigInteger to match types and avoid FK mismatch.
            $table->bigInteger('pengguna_id');
            $table->bigInteger('materi_id');
            $table->timestamps();

            $table->unique(['pengguna_id','materi_id']);

            $table->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
            $table->foreign('materi_id')->references('id')->on('materi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rak_buku');
    }
};
