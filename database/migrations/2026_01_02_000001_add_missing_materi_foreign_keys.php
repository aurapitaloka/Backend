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
        $needsMataPelajaran = !Schema::hasColumn('materi', 'mata_pelajaran_id');
        $needsLevel = !Schema::hasColumn('materi', 'level_id');

        if ($needsMataPelajaran || $needsLevel) {
            Schema::table('materi', function (Blueprint $table) use ($needsMataPelajaran, $needsLevel) {
                if ($needsMataPelajaran) {
                    $table->foreignId('mata_pelajaran_id')->nullable()->after('deskripsi');
                }
                if ($needsLevel) {
                    $table->foreignId('level_id')->nullable()->after('mata_pelajaran_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasMataPelajaran = Schema::hasColumn('materi', 'mata_pelajaran_id');
        $hasLevel = Schema::hasColumn('materi', 'level_id');

        if ($hasMataPelajaran || $hasLevel) {
            Schema::table('materi', function (Blueprint $table) use ($hasMataPelajaran, $hasLevel) {
                if ($hasLevel) {
                    $table->dropColumn('level_id');
                }
                if ($hasMataPelajaran) {
                    $table->dropColumn('mata_pelajaran_id');
                }
            });
        }
    }
};
