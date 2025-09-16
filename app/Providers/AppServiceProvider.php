<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Article;
use App\Models\UserSectionSettings;
use App\Observers\UserObserver;
use App\Observers\ArticleObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Принудительно используем HTTPS в продакшене
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Делаем $currentUserId доступным во всех blade шаблонах
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('currentUserId', auth()->user()->id);
            }
        });

        // Делаем настройки секций доступными в админских шаблонах
        View::composer('admin.*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $sectionSettings = $user->sectionSettings()
                    ->visible()
                    ->get()
                    ->keyBy('section_key');
                
                $view->with('userSectionSettings', $sectionSettings);
            }
        });

        // Регистрируем Observers для автоматического обновления sitemap
        User::observe(UserObserver::class);
        Article::observe(ArticleObserver::class);
    }
}
