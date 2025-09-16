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
        Schema::table('articles', function (Blueprint $table) {
            // Индекс для оптимизации поиска по заголовку
            $table->index(['title'], 'articles_title_index');
            
            // Индекс для оптимизации поиска по excerpt
            $table->index(['excerpt'], 'articles_excerpt_index');
            
            // Составной индекс для оптимизации запросов по пользователю и статусу публикации
            $table->index(['user_id', 'is_published'], 'articles_user_published_index');
            
            // Индекс для оптимизации сортировки по дате создания
            $table->index(['created_at'], 'articles_created_at_index');
            
            // Индекс для slug (если его еще нет)
            if (!Schema::hasIndex('articles', 'articles_slug_index')) {
                $table->index(['slug'], 'articles_slug_index');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Индекс для оптимизации поиска по имени пользователя
            $table->index(['name'], 'users_name_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_title_index');
            $table->dropIndex('articles_excerpt_index');
            $table->dropIndex('articles_user_published_index');
            $table->dropIndex('articles_created_at_index');
            
            if (Schema::hasIndex('articles', 'articles_slug_index')) {
                $table->dropIndex('articles_slug_index');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_name_index');
        });
    }
};