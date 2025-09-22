<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    protected $signature = 'seo:sitemap';
    protected $description = 'Генерация XML sitemap для поисковых систем';

    public function handle()
    {
        $this->info('🚀 Генерация XML sitemap...');

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Главные страницы пользователей
        $users = User::all();
        foreach ($users as $user) {
            // Главная страница пользователя
            $sitemap .= $this->generateUrlEntry(
                route('user.show', $user->username),
                $user->updated_at,
                'weekly',
                '0.8'
            );
            
            // Страница статей пользователя
            $sitemap .= $this->generateUrlEntry(
                route('articles.index', $user->username),
                $user->updated_at,
                'weekly',
                '0.7'
            );

        }

        // Статьи
        $articles = Article::with('user')->where('is_published', true)->get();
        foreach ($articles as $article) {
            $sitemap .= $this->generateUrlEntry(
                route('articles.show', ['username' => $article->user->username, 'slug' => $article->slug]),
                $article->updated_at,
                'monthly',
                '0.6'
            );
        }

        $sitemap .= '</urlset>';

        // Сохраняем sitemap напрямую в public директорию
        file_put_contents(public_path('sitemap.xml'), $sitemap);

        $this->info('✅ XML sitemap успешно создан: ' . public_path('sitemap.xml'));
        $this->info('📊 Добавлено URL:');
        $this->info("   Пользователей (главные страницы): {$users->count()}");
        $this->info("   Пользователей (страницы статей): {$users->count()}");
        $this->info("   Статей: {$articles->count()}");
        $this->info("   Всего: " . ($users->count() * 2 + $articles->count()));
    }

    private function generateUrlEntry($url, $lastmod, $changefreq, $priority)
    {
        return "  <url>\n" .
               "    <loc>{$url}</loc>\n" .
               "    <lastmod>{$lastmod->format('Y-m-d')}</lastmod>\n" .
               "    <changefreq>{$changefreq}</changefreq>\n" .
               "    <priority>{$priority}</priority>\n" .
               "  </url>\n";
    }
}
