<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->seed();

        Route::get('/api/protected', function () {
            return response()->json(['message' => 'Protected route']);
        })->middleware('auth:api');
    }

    public function test_can_create_token_with_valid_creds()
    {
        $response = $this->postJson('/api/token', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['token']]);;
    }


    public function test_cannot_create_token_with_invalid_creds()
    {
        $response = $this->postJson('/api/token', [
            'email' => 'invalid@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }

    public function test_can_access_protected_route_with_token()
    {
        $response = $this->postJson('/api/token', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $token = $response->json('data.token');

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/protected');
        $response->assertStatus(200)
            ->assertJson(['message' => 'Protected route']);
    }

    public function test_cannot_access_protected_route_without_token()
    {
        $response = $this->getJson('/api/protected');
        $response->assertStatus(401);
    }

}
