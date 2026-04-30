<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kuis', function (Blueprint $table) {
            $table->foreignId('materi_bab_id')
                ->nullable()
                ->after('materi_id')
                ->constrained('materi_bab')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kuis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('materi_bab_id');
        });
    }
};
