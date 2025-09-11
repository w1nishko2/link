<?php

namespace App\Observers;

use App\Models\Article;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        // Всегда обновляем sitemap при создании статьи
        $this->updateSitemap("Article created (ID: {$article->id}, Published: " . ($article->is_published ? 'Yes' : 'No') . ")");
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        // Обновляем sitemap если статья опубликована или изменился статус публикации
        if ($article->is_published || $article->wasChanged('is_published')) {
            $this->updateSitemap("Article updated (ID: {$article->id}, Published: " . ($article->is_published ? 'Yes' : 'No') . ")");
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        $this->updateSitemap('Article deleted');
    }

    /**
     * Update sitemap with error handling
     */
    private function updateSitemap(string $reason): void
    {
        try {
            Artisan::call('seo:sitemap');
            Log::info("SEO: Sitemap автоматически обновлен - {$reason}");
        } catch (\Exception $e) {
            Log::error("SEO: Ошибка при автоматическом обновлении sitemap - {$reason}", [
                'error' => $e->getMessage()
            ]);
        }
    }
}
