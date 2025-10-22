<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use App\Models\Currency;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class WatchDogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_watchdog()
    {
        // 1. Create a user
        $user = User::create([
            'id' => Str::uuid(),
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('secret'),
        ]);

        // 2. Issue a JWT token
        $token = JWTAuth::fromUser($user);

        // 3. Create a currency with UUID
        $currency = Currency::create([
            'id' => Str::uuid(),
            'coin_id' => 'BTC',          // not-null
            'name' => 'Bitcoin',
            'symbol' => 'BTC',
            'current_price' => 50000,
            'price_change_percentage_24h' => 2.5,
            'market_cap' => 1000000000,
            'image_url' => 'https://via.placeholder.com/50',
        ]);

        // 4. Create WatchDog via API
        $response = $this->postJson('/api/v1/watchdogs', [
            'currency_id' => $currency->id,
            'target_price' => 60000,
        ], ['Authorization' => "Bearer $token"]);


        // 5. Assertions
        $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'user_id',
            'currency_id',
            'target_price',
            'created_at',
            'updated_at',
        ]);
       
        $this->assertDatabaseHas('watch_dogs', [
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'target_price' => 60000,
        ]);
    }
}
