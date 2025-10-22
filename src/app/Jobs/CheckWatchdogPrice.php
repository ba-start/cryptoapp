<?php

namespace App\Jobs;

use App\Mail\WatchdogAlertMail;
use App\Models\WatchDog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CheckWatchdogPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $watchdog;

    public function __construct(WatchDog $watchdog)
    {
        $this->watchdog = $watchdog;
    }

    public function handle()
    {
        $currency = $this->watchdog->currency;
        if (!$currency) return;

        $currentPrice = $currency->current_price ?? 0;
        $targetPrice = $this->watchdog->target_price;

        if ($targetPrice > 0 && bccomp((string)$currentPrice, (string)$targetPrice, 8) === 0) {
            Mail::to($this->watchdog->user->email)
                ->send(new WatchdogAlertMail($this->watchdog, $currentPrice));

            $this->watchdog->notified_at = now();
            $this->watchdog->save();
        }
    }
}
