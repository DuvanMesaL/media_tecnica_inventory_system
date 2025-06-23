<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckQueueStatus extends Command
{
    protected $signature = 'queue:status';
    protected $description = 'Check queue status and pending jobs';

    public function handle()
    {
        $this->info('Checking queue status...');

        // Check queue connection
        $queueConnection = config('queue.default');
        $this->info('Queue connection: ' . $queueConnection);

        if ($queueConnection === 'database') {
            // Check jobs table
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();

            $this->info('Pending jobs: ' . $pendingJobs);
            $this->info('Failed jobs: ' . $failedJobs);

            if ($pendingJobs > 0) {
                $this->warn('⚠️  There are pending jobs in the queue. Make sure queue worker is running.');
                $this->info('Run: php artisan queue:work');
            }

            if ($failedJobs > 0) {
                $this->error('❌ There are failed jobs. Check with: php artisan queue:failed');

                // Show recent failed jobs
                $recentFailed = DB::table('failed_jobs')
                    ->orderBy('failed_at', 'desc')
                    ->limit(5)
                    ->get(['payload', 'exception', 'failed_at']);

                foreach ($recentFailed as $job) {
                    $payload = json_decode($job->payload, true);
                    $this->error('Failed job: ' . ($payload['displayName'] ?? 'Unknown') . ' at ' . $job->failed_at);
                    $this->error('Exception: ' . substr($job->exception, 0, 200) . '...');
                }
            }
        }
    }
}
