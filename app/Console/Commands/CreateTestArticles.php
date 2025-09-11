<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Str;

class CreateTestArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-articles {count=20}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test articles for pagination testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');
        $user = User::first();
        
        if (!$user) {
            $this->error('Нет пользователей в базе данных');
            return;
        }

        for ($i = 1; $i <= $count; $i++) {
            $title = "Тестовая статья №{$i}";
            Article::create([
                'user_id' => $user->id,
                'title' => $title,
                'slug' => Str::slug($title . '-' . time() . '-' . $i),
                'excerpt' => "Краткое описание тестовой статьи номер {$i}. Это статья для проверки пагинации.",
                'content' => "Содержимое тестовой статьи номер {$i}. Здесь много интересного текста о разных вещах. Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                'is_published' => true,
                'read_time' => rand(3, 15),
                'created_at' => now()->subMinutes(rand(1, 1000)),
            ]);
        }

        $this->info("Создано {$count} тестовых статей");
    }
}
