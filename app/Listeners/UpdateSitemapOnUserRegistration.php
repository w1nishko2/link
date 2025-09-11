<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class UpdateSitemapOnUserRegistration
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        try {
            // Автоматически обновляем sitemap при регистрации нового пользователя
            Artisan::call('seo:sitemap');
            
            Log::info('SEO: Sitemap автоматически обновлен после регистрации нового пользователя');
            
        } catch (\Exception $e) {
            Log::error('SEO: Ошибка при автоматическом обновлении sitemap', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
