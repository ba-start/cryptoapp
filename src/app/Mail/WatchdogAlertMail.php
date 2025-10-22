<?php

namespace App\Mail;

use App\Models\WatchDog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WatchdogAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $watchdog;
    public $currentPrice;

    public function __construct(WatchDog $watchdog, $currentPrice)
    {
        $this->watchdog = $watchdog;
        $this->currentPrice = $currentPrice;
    }

    public function build()
    {
        return $this->subject("Watchdog Alert: {$this->watchdog->currency->name}")
                    ->markdown('emails.watchdog.alert')
                    ->with([
                        'watchdog' => $this->watchdog,
                        'currentPrice' => $this->currentPrice,
                    ]);
    }
}
