<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class UpdateSitemapOnContentChange
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        try {
            // Автоматически обновляем sitemap при изменении контента
            Artisan::call('seo:sitemap');
            
            Log::info('SEO: Sitemap автоматически обновлен после изменения контента');
            
        } catch (\Exception $e) {
            Log::error('SEO: Ошибка при автоматическом обновлении sitemap', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
