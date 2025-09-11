<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Article;

class CheckData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверяет данные пользователей и статей';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Проверка данных ===');
        
        // Проверяем пользователей
        $users = User::all(['id', 'name', 'username']);
        $this->info('Пользователи в базе данных:');
        foreach($users as $user) {
            $this->line("ID: {$user->id}, Name: {$user->name}, Username: {$user->username}");
        }
        
        $this->info('');
        
        // Проверяем статьи
        $articles = Article::with('user')->get(['id', 'user_id', 'title', 'slug', 'is_published']);
        $this->info('Статьи в базе данных:');
        foreach($articles as $article) {
            $published = $article->is_published ? 'Да' : 'Нет';
            $username = $article->user ? $article->user->username : 'N/A';
            $this->line("ID: {$article->id}, Title: {$article->title}, Slug: {$article->slug}, Published: {$published}, User: {$username}");
        }
        
        $this->info('');
        $this->info('Тестовые URL:');
        foreach($users as $user) {
            $this->line("Статьи пользователя: /user/{$user->username}/articles");
            
            $userArticles = $user->articles()->published()->get();
            foreach($userArticles as $article) {
                $this->line("  Статья: /user/{$user->username}/article/{$article->slug}");
            }
        }
    }
}
