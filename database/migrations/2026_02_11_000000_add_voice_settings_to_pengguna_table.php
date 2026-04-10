<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->string('asr_lang', 10)->nullable();
            $table->string('tts_lang', 10)->nullable();
            $table->decimal('tts_rate', 3, 1)->nullable();
            $table->boolean('auto_voice_nav')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropColumn(['asr_lang', 'tts_lang', 'tts_rate', 'auto_voice_nav']);
        });
    }
};
