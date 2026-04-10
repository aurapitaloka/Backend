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
        Schema::table('catatan_siswa', function (Blueprint $table) {
            $table->unsignedBigInteger('materi_id')->nullable()->after('pengguna_id');
            $table->index('materi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_siswa', function (Blueprint $table) {
            $table->dropIndex(['materi_id']);
            $table->dropColumn('materi_id');
        });
    }
};
