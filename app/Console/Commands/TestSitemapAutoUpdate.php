<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class TestSitemapAutoUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:test-sitemap-auto-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тестирование автоматического обновления sitemap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Тестирование автоматического обновления sitemap...');

        // Проверяем текущее состояние sitemap
        $sitemapPath = public_path('sitemap.xml');
        $initialModTime = file_exists($sitemapPath) ? filemtime($sitemapPath) : 0;
        
        $this->info("📋 Исходное время изменения sitemap: " . ($initialModTime ? date('Y-m-d H:i:s', $initialModTime) : 'файл не существует'));

        // Тест 1: Обновление профиля пользователя
        $this->info("\n🔄 Тест 1: Обновление профиля пользователя...");
        $user = User::first();
        if ($user) {
            $originalBio = $user->bio;
            $user->bio = 'Тестовое обновление био для проверки sitemap - ' . time();
            $user->save();
            
            sleep(2); // Даем время на обновление
            
            $newModTime = file_exists($sitemapPath) ? filemtime($sitemapPath) : 0;
            
            if ($newModTime > $initialModTime) {
                $this->info("✅ Sitemap автоматически обновлен!");
                $this->info("   Новое время: " . date('Y-m-d H:i:s', $newModTime));
            } else {
                $this->error("❌ Sitemap НЕ был обновлен автоматически");
            }
            
            // Возвращаем исходное значение
            $user->bio = $originalBio;
            $user->save();
        } else {
            $this->error("❌ Пользователь не найден для тестирования");
        }

        // Тест 2: Создание новой статьи
        $this->info("\n🔄 Тест 2: Создание новой статьи...");
        if ($user) {
            $this->info("   Создаем статью...");
            
            $testArticle = new \App\Models\Article([
                'user_id' => $user->id,
                'title' => 'Тестовая статья для проверки sitemap',
                'excerpt' => 'Краткое описание тестовой статьи',
                'content' => 'Полный текст тестовой статьи для проверки автоматического обновления sitemap.',
                'slug' => 'test-sitemap-article-' . time(),
                'is_published' => true
            ]);
            
            $testArticle->save();
            
            $this->info("   Статья создана с ID: {$testArticle->id}");
            
            sleep(2); // Даем больше времени
            
            $newestModTime = file_exists($sitemapPath) ? filemtime($sitemapPath) : 0;
            
            if ($newestModTime > $newModTime) {
                $this->info("✅ Sitemap автоматически обновлен при создании статьи!");
                $this->info("   Новое время: " . date('Y-m-d H:i:s', $newestModTime));
            } else {
                $this->error("❌ Sitemap НЕ был обновлен при создании статьи");
                $this->info("   Время до: " . date('Y-m-d H:i:s', $newModTime));
                $this->info("   Время после: " . date('Y-m-d H:i:s', $newestModTime));
            }
            
            // Удаляем тестовую статью
            $testArticle->delete();
            
            $this->info("🗑️ Тестовая статья удалена");
        }

        $this->info("\n✅ Тестирование завершено!");
        
        // Показываем инструкцию
        $this->info("\n📖 Для тестирования регистрации нового пользователя:");
        $this->info("   Зарегистрируйте нового пользователя через веб-интерфейс");
        $this->info("   и проверьте логи: tail -f storage/logs/laravel.log");

        return 0;
    }
}
