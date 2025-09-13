<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->updateSitemap('User created');
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Обновляем sitemap только если изменились важные для SEO поля
        if ($user->wasChanged(['name', 'username', 'bio', 'account_type'])) {
            $this->updateSitemap('User updated');
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->updateSitemap('User deleted');
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
