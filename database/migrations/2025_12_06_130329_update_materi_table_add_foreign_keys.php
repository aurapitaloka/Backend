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
        Schema::table('materi', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['mata_pelajaran', 'level']);
            
            // Tambah kolom baru dengan foreign key
            $table->foreignId('mata_pelajaran_id')->nullable()->after('deskripsi')->constrained('mata_pelajaran')->onDelete('set null');
            $table->foreignId('level_id')->nullable()->after('mata_pelajaran_id')->constrained('level')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            // Hapus foreign key
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropForeign(['level_id']);
            
            // Hapus kolom baru
            $table->dropColumn(['mata_pelajaran_id', 'level_id']);
            
            // Kembalikan kolom lama
            $table->string('mata_pelajaran', 100)->nullable()->after('deskripsi');
            $table->string('level', 50)->nullable()->after('mata_pelajaran');
        });
    }
};
