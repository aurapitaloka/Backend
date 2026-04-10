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
        Schema::table('level', function (Blueprint $table) {
            $table->string('nama', 100)->unique()->after('id');
            $table->text('deskripsi')->nullable()->after('nama');
            $table->boolean('status_aktif')->default(true)->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('level', function (Blueprint $table) {
            $table->dropColumn(['nama', 'deskripsi', 'status_aktif']);
        });
    }
};
