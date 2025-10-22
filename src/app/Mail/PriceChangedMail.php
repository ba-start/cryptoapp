<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PriceChangedMail extends Mailable {
    public $user;
    public $currency;
    public $oldPrice;
    public $newPrice;

    public function __construct($user, $currency, $oldPrice, $newPrice) {
        $this->user=$user; $this->currency=$currency; $this->oldPrice=$oldPrice; $this->newPrice=$newPrice;
    }

    public function build() {
        return $this->subject("Price changed: {$this->currency->name}")
            ->view('emails.price_changed')
            ->with([
                'user'=>$this->user,
                'currency'=>$this->currency,
                'oldPrice'=>$this->oldPrice,
                'newPrice'=>$this->newPrice,
            ]);
    }
}
