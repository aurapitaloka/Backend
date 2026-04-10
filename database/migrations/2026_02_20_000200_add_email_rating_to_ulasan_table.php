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
        Schema::table('ulasan', function (Blueprint $table) {
            if (!Schema::hasColumn('ulasan', 'email')) {
                $table->string('email', 150)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('ulasan', 'rating')) {
                $table->unsignedTinyInteger('rating')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulasan', function (Blueprint $table) {
            if (Schema::hasColumn('ulasan', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('ulasan', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
