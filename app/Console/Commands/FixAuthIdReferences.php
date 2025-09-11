<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixAuthIdReferences extends Command
{
    protected $signature = 'fix:auth-id';
    protected $description = 'Исправляет все ссылки auth()->id() на auth()->user()->id в blade файлах';

    public function handle()
    {
        $viewsPath = resource_path('views');
        
        // Рекурсивно обходим все blade файлы
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($viewsPath)
        );
        
        $bladeFiles = [];
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $bladeFiles[] = $file->getPathname();
            }
        }
        
        foreach ($bladeFiles as $filePath) {
            $content = file_get_contents($filePath);
            $originalContent = $content;
            
            // Заменяем auth()->id() на $currentUserId в шаблонах
            $content = preg_replace('/auth\(\)->id\(\)/', '$currentUserId', $content);
            
            if ($content !== $originalContent) {
                file_put_contents($filePath, $content);
                $this->info("Обновлен файл: " . str_replace(resource_path(), '', $filePath));
            }
        }
        
        $this->info('Все blade файлы обновлены!');
        return 0;
    }
}
