<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateWeebsUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-weebs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать пользователя @weebs для GPT генератора статей';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Проверяем, существует ли уже пользователь
        $existingUser = User::where('username', 'weebs')->first();
        
        if ($existingUser) {
            $this->info("Пользователь @weebs уже существует!");
            $this->table(
                ['ID', 'Имя', 'Username', 'Роль', 'Email'],
                [[$existingUser->id, $existingUser->name, $existingUser->username, $existingUser->role, $existingUser->email ?? 'не указан']]
            );
            return 0;
        }

        // Создаем нового пользователя
        $user = User::create([
            'name' => 'AI Автор',
            'username' => 'weebs',
            'phone' => '79999999999', // Фиктивный телефон
            'password' => Hash::make('password123'), // Временный пароль
            'role' => 'user', // Обычный пользователь
            'bio' => 'Автор статей, созданных с помощью искусственного интеллекта. Специализируется на создании качественного и полезного контента по различным темам.',
        ]);

        $this->info("Пользователь @weebs успешно создан!");
        $this->table(
            ['ID', 'Имя', 'Username', 'Роль', 'Дата создания'],
            [[$user->id, $user->name, $user->username, $user->role, $user->created_at->format('Y-m-d H:i:s')]]
        );

        $this->warn("Важно: Временный пароль: password123");
        $this->warn("Рекомендуется изменить пароль через админку.");

        return 0;
    }
}