<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_currencies()
    {
        Currency::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/currencies');

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }

    public function test_can_show_currency()
    {
        $currencies = \App\Models\Currency::factory()->count(5)->create();
        $response = $this->getJson('/api/v1/currencies');
        $response->assertStatus(200)
                ->assertJsonCount(5);
    }
}
