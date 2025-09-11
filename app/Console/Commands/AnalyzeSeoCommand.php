<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\User;
use App\Helpers\SeoHelper;

class AnalyzeSeoCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'seo:analyze {--user= : ID пользователя для анализа} {--articles : Анализировать только статьи} {--detailed : Детальный анализ}';

    /**
     * The console command description.
     */
    protected $description = 'Анализирует SEO оптимизацию контента сайта';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Запуск SEO анализа...');
        $this->newLine();

        $userId = $this->option('user');
        $articlesOnly = $this->option('articles');
        $detailed = $this->option('detailed');

        // Анализ статей
        if (!$userId || $articlesOnly) {
            $this->analyzeArticles($userId, $detailed);
        }

        // Анализ пользователей
        if (!$articlesOnly) {
            $this->analyzeUsers($userId, $detailed);
        }

        $this->newLine();
        $this->info('✅ Анализ завершен!');
    }

    /**
     * Анализирует SEO статей
     */
    private function analyzeArticles($userId = null, $detailed = false)
    {
        $this->info('📄 Анализ статей:');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $query = Article::with('user');
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $articles = $query->get();

        if ($articles->isEmpty()) {
            $this->warn('❌ Статьи не найдены');
            return;
        }

        $issues = [];
        $totalArticles = $articles->count();
        $problematicArticles = 0;

        foreach ($articles as $article) {
            $titleAnalysis = SeoHelper::validateTitleLength($article->title);
            $descriptionAnalysis = $article->excerpt ? 
                SeoHelper::validateDescriptionLength($article->excerpt) : 
                ['status' => 'missing', 'message' => 'Отсутствует краткое описание'];
            
            $contentAnalysis = SeoHelper::analyzeContent($article->title, $article->content);

            $hasIssues = !$titleAnalysis['is_optimal'] || 
                        $descriptionAnalysis['status'] !== 'optimal' || 
                        !$contentAnalysis['is_sufficient_length'];

            if ($hasIssues) {
                $problematicArticles++;
            }

            if ($detailed || $hasIssues) {
                $this->displayArticleAnalysis($article, $titleAnalysis, $descriptionAnalysis, $contentAnalysis, $hasIssues);
            }

            if ($hasIssues) {
                $issues[] = [
                    'type' => 'article',
                    'id' => $article->id,
                    'title' => $article->title,
                    'user' => $article->user->name
                ];
            }
        }

        // Статистика
        $this->newLine();
        $this->info("📊 Статистика статей:");
        $this->line("Всего статей: {$totalArticles}");
        $this->line("Статей с проблемами: {$problematicArticles}");
        $this->line("Процент оптимизации: " . round((($totalArticles - $problematicArticles) / $totalArticles) * 100, 2) . "%");
        $this->newLine();
    }

    /**
     * Анализирует SEO профилей пользователей
     */
    private function analyzeUsers($userId = null, $detailed = false)
    {
        $this->info('👤 Анализ профилей пользователей:');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $query = User::query();
        if ($userId) {
            $query->where('id', $userId);
        }
        
        $users = $query->get();

        if ($users->isEmpty()) {
            $this->warn('❌ Пользователи не найдены');
            return;
        }

        $totalUsers = $users->count();
        $incompleteProfiles = 0;

        foreach ($users as $user) {
            $profileCompletion = $this->calculateProfileCompletion($user);
            
            if ($profileCompletion < 80) {
                $incompleteProfiles++;
            }

            if ($detailed || $profileCompletion < 80) {
                $this->displayUserAnalysis($user, $profileCompletion);
            }
        }

        $this->newLine();
        $this->info("📊 Статистика профилей:");
        $this->line("Всего профилей: {$totalUsers}");
        $this->line("Неполных профилей: {$incompleteProfiles}");
        $this->line("Процент заполненности: " . round((($totalUsers - $incompleteProfiles) / $totalUsers) * 100, 2) . "%");
        $this->newLine();
    }

    /**
     * Отображает анализ статьи
     */
    private function displayArticleAnalysis($article, $titleAnalysis, $descriptionAnalysis, $contentAnalysis, $hasIssues)
    {
        $status = $hasIssues ? '❌' : '✅';
        $this->line("{$status} {$article->title} (ID: {$article->id})");
        
        if ($hasIssues || $this->option('detailed')) {
            $this->line("   👤 Автор: {$article->user->name}");
            
            if (!$titleAnalysis['is_optimal']) {
                $this->line("   🔸 Заголовок: {$titleAnalysis['message']} ({$titleAnalysis['length']} символов)");
            }
            
            if ($descriptionAnalysis['status'] !== 'optimal') {
                $this->line("   🔸 Описание: {$descriptionAnalysis['message']}");
            }
            
            if (!$contentAnalysis['is_sufficient_length']) {
                $this->line("   🔸 Контент: Недостаточно слов ({$contentAnalysis['word_count']}/300)");
            }
            
            if (!$contentAnalysis['title_in_content']) {
                $this->line("   🔸 Ключевые слова из заголовка не найдены в тексте");
            }
            
            if (!empty($contentAnalysis['recommendations'])) {
                foreach ($contentAnalysis['recommendations'] as $recommendation) {
                    $this->line("   💡 {$recommendation}");
                }
            }
            
            $this->newLine();
        }
    }

    /**
     * Отображает анализ пользователя
     */
    private function displayUserAnalysis($user, $completion)
    {
        $status = $completion >= 80 ? '✅' : '❌';
        $this->line("{$status} {$user->name} (@{$user->username}) - {$completion}% заполнен");
        
        if ($completion < 80 || $this->option('detailed')) {
            $missing = [];
            
            if (!$user->bio) $missing[] = 'Описание профиля';
            if (!$user->avatar) $missing[] = 'Аватар';
            if (!$user->background_image) $missing[] = 'Фоновое изображение';
            if (!$user->telegram_url && !$user->whatsapp_url && !$user->vk_url) {
                $missing[] = 'Социальные сети';
            }
            
            if (!empty($missing)) {
                $this->line("   🔸 Отсутствует: " . implode(', ', $missing));
            }
            
            $this->newLine();
        }
    }

    /**
     * Вычисляет процент заполненности профиля
     */
    private function calculateProfileCompletion($user)
    {
        $fields = [
            'name' => !empty($user->name),
            'username' => !empty($user->username),
            'bio' => !empty($user->bio),
            'avatar' => !empty($user->avatar),
            'background_image' => !empty($user->background_image),
            'social_links' => !empty($user->telegram_url) || !empty($user->whatsapp_url) || 
                            !empty($user->vk_url) || !empty($user->youtube_url) || !empty($user->ok_url)
        ];
        
        $filledFields = array_sum($fields);
        $totalFields = count($fields);
        
        return round(($filledFields / $totalFields) * 100);
    }
}
