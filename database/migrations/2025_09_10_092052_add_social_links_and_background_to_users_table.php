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
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_url')->nullable()->after('bio');
            $table->string('whatsapp_url')->nullable()->after('telegram_url');
            $table->string('vk_url')->nullable()->after('whatsapp_url');
            $table->string('youtube_url')->nullable()->after('vk_url');
            $table->string('ok_url')->nullable()->after('youtube_url'); // Одноклассники
            $table->string('background_image')->nullable()->after('ok_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'telegram_url',
                'whatsapp_url', 
                'vk_url',
                'youtube_url',
                'ok_url',
                'background_image'
            ]);
        });
    }
};
