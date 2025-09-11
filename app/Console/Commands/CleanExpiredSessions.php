<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clean {--days=30 : Number of days to keep sessions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired session files older than specified days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $sessionPath = storage_path('framework/sessions');
        
        if (!File::exists($sessionPath)) {
            $this->error('Session directory does not exist: ' . $sessionPath);
            return 1;
        }
        
        $cutoffTime = Carbon::now()->subDays($days)->timestamp;
        $files = File::files($sessionPath);
        $deletedCount = 0;
        
        foreach ($files as $file) {
            if ($file->getMTime() < $cutoffTime) {
                File::delete($file->getPathname());
                $deletedCount++;
            }
        }
        
        $this->info("Deleted {$deletedCount} expired session files older than {$days} days.");
        
        return 0;
    }
}
