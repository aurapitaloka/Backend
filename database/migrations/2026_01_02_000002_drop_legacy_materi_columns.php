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
        $dropMataPelajaran = Schema::hasColumn('materi', 'mata_pelajaran');
        $dropLevel = Schema::hasColumn('materi', 'level');

        if ($dropMataPelajaran || $dropLevel) {
            Schema::table('materi', function (Blueprint $table) use ($dropMataPelajaran, $dropLevel) {
                if ($dropMataPelajaran) {
                    $table->dropColumn('mata_pelajaran');
                }
                if ($dropLevel) {
                    $table->dropColumn('level');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasMataPelajaran = Schema::hasColumn('materi', 'mata_pelajaran');
        $hasLevel = Schema::hasColumn('materi', 'level');

        if (!$hasMataPelajaran || !$hasLevel) {
            Schema::table('materi', function (Blueprint $table) use ($hasMataPelajaran, $hasLevel) {
                if (!$hasMataPelajaran) {
                    $table->string('mata_pelajaran', 100)->nullable()->after('deskripsi');
                }
                if (!$hasLevel) {
                    $table->string('level', 50)->nullable()->after('mata_pelajaran');
                }
            });
        }
    }
};
