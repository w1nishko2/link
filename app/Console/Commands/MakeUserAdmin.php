<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Назначить пользователя администратором';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            $this->error("Пользователь с username '{$username}' не найден!");
            return 1;
        }
        
        if ($user->role === 'admin') {
            $this->info("Пользователь '{$user->name}' ({$username}) уже является администратором!");
            return 0;
        }
        
        $user->update(['role' => 'admin']);
        
        $this->info("Пользователь '{$user->name}' ({$username}) успешно назначен администратором!");
        return 0;
    }
}
