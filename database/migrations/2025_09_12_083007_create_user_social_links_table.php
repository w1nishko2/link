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
        Schema::create('user_social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('service_name'); // Название сервиса (например: "GitHub", "Instagram", "LinkedIn")
            $table->string('url'); // URL ссылки
            $table->string('icon_class'); // Bootstrap Icons класс (например: "bi-github", "bi-instagram")
            $table->integer('order')->default(0); // Порядок отображения
            $table->timestamps();
            
            // Индексы для оптимизации
            $table->index(['user_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_social_links');
    }
};
