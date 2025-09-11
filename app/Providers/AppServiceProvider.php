<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Article;
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
        // Делаем $currentUserId доступным во всех blade шаблонах
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('currentUserId', auth()->user()->id);
            }
        });

        // Регистрируем Observers для автоматического обновления sitemap
        User::observe(UserObserver::class);
        Article::observe(ArticleObserver::class);
    }
}
