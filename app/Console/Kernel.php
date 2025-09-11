<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Очищаем старые сессии раз в неделю (сессии старше 30 дней)
        $schedule->command('sessions:clean --days=30')->weekly();
        
        // Можно также добавить ежедневную очистку для очень старых сессий
        $schedule->command('sessions:clean --days=90')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
