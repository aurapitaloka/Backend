<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $constraint = $this->foreignKeyName('log_akses_materi', 'materi_id');

        if ($constraint) {
            Schema::table('log_akses_materi', function (Blueprint $table) use ($constraint) {
                $table->dropForeign($constraint);
            });
        }

        Schema::table('log_akses_materi', function (Blueprint $table) {
            $table->foreign('materi_id')
                ->references('id')
                ->on('materi')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $constraint = $this->foreignKeyName('log_akses_materi', 'materi_id');

        if ($constraint) {
            Schema::table('log_akses_materi', function (Blueprint $table) use ($constraint) {
                $table->dropForeign($constraint);
            });
        }

        Schema::table('log_akses_materi', function (Blueprint $table) {
            $table->foreign('materi_id')
                ->references('id')
                ->on('materi');
        });
    }

    private function foreignKeyName(string $table, string $column): ?string
    {
        $database = DB::getDatabaseName();

        $result = DB::selectOne(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ?
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL
             LIMIT 1",
            [$database, $table, $column]
        );

        return $result?->CONSTRAINT_NAME;
    }
};
