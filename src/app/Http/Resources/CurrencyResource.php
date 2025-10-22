<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'coin_id' => $this->coin_id,
            'image_url' => $this->image_url,
            'symbol' => $this->symbol,
            'current_price' => $this->current_price,
            'price_change_percentage_24h' => $this->price_change_percentage_24h,
            'market_cap' => $this->market_cap,
            'updated_at' => $this->updated_at,
        ];
    }
}
