<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixOwnershipChecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:ownership-checks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Убирает строгие проверки владельца в AdminController';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controllerPath = app_path('Http/Controllers/AdminController.php');
        
        if (!file_exists($controllerPath)) {
            $this->error('AdminController.php не найден!');
            return 1;
        }

        $content = file_get_contents($controllerPath);
        
        // Паттерны для поиска и замены проверок владельца
        $patterns = [
            // Найти: if ($service->user_id !== auth()->id()) { abort(403); }
            '/if\s*\(\s*\$(\w+)->user_id\s*!==\s*auth\(\)->id\(\)\s*\)\s*\{\s*abort\(403\);\s*\}/s' => 
                '// Проверка владельца отключена - админы могут управлять всем контентом
                // if ($1->user_id !== auth()->id()) {
                //     abort(403);
                // }',
        ];

        $originalContent = $content;
        
        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        if ($content !== $originalContent) {
            file_put_contents($controllerPath, $content);
            $this->info('✅ Проверки владельца успешно отключены в AdminController!');
            $this->info('🔧 Теперь админы могут управлять всем контентом.');
        } else {
            $this->info('ℹ️ Проверки владельца уже отключены или не найдены.');
        }

        return 0;
    }
}
