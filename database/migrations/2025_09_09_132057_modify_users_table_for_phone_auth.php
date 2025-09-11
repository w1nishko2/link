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
            // Удаляем email поля
            $table->dropColumn(['email', 'email_verified_at']);
            
            // Добавляем поле для номера телефона
            $table->string('phone')->unique()->after('name');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Возвращаем email поля
            $table->string('email')->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            
            // Удаляем phone поля
            $table->dropColumn(['phone', 'phone_verified_at']);
        });
    }
};
