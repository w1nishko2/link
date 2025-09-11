<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ViewGptLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:gpt {--lines=50 : Количество последних строк для показа}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Показать логи GPT генератора статей';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lines = $this->option('lines');
        $logPath = storage_path('logs');
        
        // Ищем файлы логов GPT
        $gptLogFiles = File::glob($logPath . '/gpt-generator-*.log');
        
        if (empty($gptLogFiles)) {
            $this->warn('Файлы логов GPT генератора не найдены.');
            $this->info('Логи будут создаваться при первом использовании генератора.');
            return 0;
        }
        
        // Сортируем файлы по дате (новые сначала)
        usort($gptLogFiles, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $latestLogFile = $gptLogFiles[0];
        
        $this->info("Показываю последние {$lines} строк из: " . basename($latestLogFile));
        $this->line('');
        
        if (File::exists($latestLogFile)) {
            $command = "tail -n {$lines} " . escapeshellarg($latestLogFile);
            $output = shell_exec($command);
            
            if ($output) {
                $this->line($output);
            } else {
                // Fallback для Windows
                $content = File::get($latestLogFile);
                $lines_array = explode("\n", $content);
                $last_lines = array_slice($lines_array, -$lines);
                $this->line(implode("\n", $last_lines));
            }
        } else {
            $this->error('Файл лога не найден: ' . $latestLogFile);
        }
        
        $this->line('');
        $this->info('Доступные файлы логов GPT:');
        foreach ($gptLogFiles as $file) {
            $size = File::size($file);
            $modified = date('Y-m-d H:i:s', filemtime($file));
            $this->line("  " . basename($file) . " (размер: " . $this->formatBytes($size) . ", изменен: {$modified})");
        }
        
        return 0;
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}