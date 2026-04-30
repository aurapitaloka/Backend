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
        Schema::table('materi_bab', function (Blueprint $table) {
            $table->string('summary_title')->nullable()->after('status_aktif');
            $table->text('summary_short')->nullable()->after('summary_title');
            $table->json('summary_key_points')->nullable()->after('summary_short');
            $table->json('summary_keywords')->nullable()->after('summary_key_points');
            $table->text('summary_memory_tip')->nullable()->after('summary_keywords');
            $table->text('summary_example')->nullable()->after('summary_memory_tip');
            $table->timestamp('summary_generated_at')->nullable()->after('summary_example');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi_bab', function (Blueprint $table) {
            $table->dropColumn([
                'summary_title',
                'summary_short',
                'summary_key_points',
                'summary_keywords',
                'summary_memory_tip',
                'summary_example',
                'summary_generated_at',
            ]);
        });
    }
};
