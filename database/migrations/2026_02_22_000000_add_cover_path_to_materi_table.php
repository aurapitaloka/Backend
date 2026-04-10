<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            if (!Schema::hasColumn('materi', 'cover_path')) {
                $table->string('cover_path', 255)->nullable()->after('file_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            if (Schema::hasColumn('materi', 'cover_path')) {
                $table->dropColumn('cover_path');
            }
        });
    }
};
