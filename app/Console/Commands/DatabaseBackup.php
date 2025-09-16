<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--disk=local : The disk to store backup on}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        $disk = $this->option('disk');
        $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.sql';
        $backupPath = storage_path('app/backups/' . $filename);

        // Создаем директорию если не существует
        if (!is_dir(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s --single-transaction --routines --triggers %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($database),
            escapeshellarg($backupPath)
        );

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(300); // 5 minutes timeout

        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Database backup failed!');
            $this->error($process->getErrorOutput());
            return 1;
        }

        $this->info("Database backup created successfully: {$filename}");

        // Очистка старых бекапов (оставляем только последние 7)
        $this->cleanOldBackups();

        return 0;
    }

    /**
     * Clean old backup files, keep only the latest 7
     */
    private function cleanOldBackups()
    {
        $backupDir = storage_path('app/backups');
        
        if (!is_dir($backupDir)) {
            return;
        }

        $files = glob($backupDir . '/backup-*.sql');
        
        if (count($files) <= 7) {
            return;
        }

        // Сортируем по времени создания (новые первыми)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // Удаляем файлы старше 7-го
        $filesToDelete = array_slice($files, 7);
        
        foreach ($filesToDelete as $file) {
            unlink($file);
            $this->info('Deleted old backup: ' . basename($file));
        }
    }
}