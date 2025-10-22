<?php 

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ImportCoinsJob;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Queue the ImportCoinsJob hourly
        $schedule->job(new \App\Jobs\ImportCoinsJob(1000, 250))
                ->hourly()
                ->onQueue('coins');

        // Check watchdogs every minute — command dispatches queued jobs internally
        $schedule->command('watchdogs:check')
                ->everyMinute();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
