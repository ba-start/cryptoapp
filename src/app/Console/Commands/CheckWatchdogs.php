<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WatchDog;
use App\Jobs\CheckWatchdogPrice;

class CheckWatchdogs extends Command
{
    protected $signature = 'watchdogs:check';
    protected $description = 'Check all active WatchDogs and dispatch email jobs if target hit';

    public function handle()
    {
        $watchdogs = WatchDog::with('currency', 'user')
            ->whereNull('notified_at')
            ->get();

        foreach ($watchdogs as $wd) {
            CheckWatchdogPrice::dispatch($wd);
        }

        $this->info("Dispatched " . count($watchdogs) . " watchdog jobs.");
    }
}
