<?php

namespace Database\Factories;

use App\Models\WatchDog;
use App\Models\User;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class WatchDogFactory extends Factory
{
    protected $model = WatchDog::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'currency_id' => Currency::factory(),
            'target_price' => $this->faker->randomFloat(4, 0, 50000),
        ];
    }
}
