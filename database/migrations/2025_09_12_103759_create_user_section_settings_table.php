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
        Schema::create('user_section_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('section_key'); // hero, services, gallery, articles, banners
            $table->string('title')->nullable(); // Заголовок секции
            $table->string('subtitle')->nullable(); // Подзаголовок секции
            $table->boolean('is_visible')->default(true); // Видимость секции
            $table->integer('order')->default(0); // Порядок отображения
            $table->json('additional_settings')->nullable(); // Дополнительные настройки в JSON
            $table->timestamps();
            
            // Индексы для оптимизации
            $table->index(['user_id', 'order']);
            $table->unique(['user_id', 'section_key']); // Уникальная секция для пользователя
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_section_settings');
    }
};
