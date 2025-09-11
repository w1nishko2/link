<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestSitemapUserRegistration extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:sitemap-user-registration';

    /**
     * The console command description.
     */
    protected $description = 'Тестирует автоматическое обновление sitemap при регистрации пользователя';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Тест автоматического обновления sitemap при регистрации ===');
        
        // Показываем текущий sitemap
        $this->info('📋 Текущий sitemap:');
        $currentSitemap = file_get_contents(public_path('sitemap.xml'));
        $this->line($currentSitemap);
        
        // Создаем тестового пользователя
        $testUsername = 'testuser' . rand(1000, 9999);
        $this->info("👤 Создаем тестового пользователя: {$testUsername}");
        
        $user = User::create([
            'name' => 'Test User ' . rand(1, 100),
            'username' => $testUsername,
            'phone' => '+7' . rand(1000000000, 9999999999),
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        
        $this->info("✅ Пользователь создан с ID: {$user->id}");
        
        // Ждем немного для обработки observer
        sleep(1);
        
        // Показываем обновленный sitemap
        $this->info('📋 Обновленный sitemap:');
        $updatedSitemap = file_get_contents(public_path('sitemap.xml'));
        $this->line($updatedSitemap);
        
        // Проверяем, добавились ли нужные URL
        $expectedUserPageUrl = "https://link/user/{$testUsername}";
        $expectedArticlesPageUrl = "https://link/user/{$testUsername}/articles";
        
        if (strpos($updatedSitemap, $expectedUserPageUrl) !== false) {
            $this->info("✅ URL главной страницы пользователя добавлен: {$expectedUserPageUrl}");
        } else {
            $this->error("❌ URL главной страницы пользователя НЕ найден: {$expectedUserPageUrl}");
        }
        
        if (strpos($updatedSitemap, $expectedArticlesPageUrl) !== false) {
            $this->info("✅ URL страницы статей пользователя добавлен: {$expectedArticlesPageUrl}");
        } else {
            $this->error("❌ URL страницы статей пользователя НЕ найден: {$expectedArticlesPageUrl}");
        }
        
        // Очищаем за собой - удаляем тестового пользователя
        $user->delete();
        $this->info("🗑️ Тестовый пользователь удален");
        
        // Обновляем sitemap после удаления
        sleep(1);
        $this->info("📋 Sitemap после удаления тестового пользователя обновлен автоматически");
        
        $this->info('✅ Тест завершен');
    }
}
