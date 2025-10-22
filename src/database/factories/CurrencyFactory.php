<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition()
    {
        return [
            'coin_id' => Str::uuid(),           // required column
            'name' => $this->faker->unique()->word,
            'symbol' => strtoupper($this->faker->lexify('???')),
            'current_price' => $this->faker->randomFloat(4, 0.01, 50000),
            'price_change_percentage_24h' => $this->faker->randomFloat(2, -50, 50),
            'market_cap' => $this->faker->numberBetween(100000, 100000000),
            'image_url' => 'https://via.placeholder.com/50',
        ];
    }
}
