<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['user', 'token']);

        $this->assertDatabaseHas('users', ['email' => 'test@test.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }
}
