<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            if (!Schema::hasColumn('materi', 'pdf_page_selection')) {
                $table->text('pdf_page_selection')->nullable()->after('jumlah_halaman');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            if (Schema::hasColumn('materi', 'pdf_page_selection')) {
                $table->dropColumn('pdf_page_selection');
            }
        });
    }
};
