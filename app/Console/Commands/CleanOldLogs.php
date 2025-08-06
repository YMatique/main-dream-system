<?php

namespace App\Console\Commands;

use App\Models\System\ActivityLog;
use Illuminate\Console\Command;

class CleanOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clean-old-logs {--days=90}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old activity logs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $days = $this->option('days');
        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
        
        $this->info("Deleted {$deleted} old log entries (older than {$days} days)");
    }
}
