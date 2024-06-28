<?php

namespace Tests\Feature;

use App\Quotes\Drivers\QuotesDriver;
use App\Quotes\Quotes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class QuotesTest extends TestCase
{
    use RefreshDatabase;

    static $quotes = [
        'quote',
        'quote',
        'quote',
        'quote',
        'quote',
        'quote',
        'quote',
    ];

    protected function setUp(): void
    {

        parent::setUp();

        $this->seed();

        Quotes::extend('test', function ($app) {
            return new class($app) implements QuotesDriver {
                public function all(): array
                {
                    return QuotesTest::$quotes;
                }
            };
        });
    }


    public function test_can_extend_quotes_driver()
    {
        $quotes = Quotes::all();

        $this->assertEquals($quotes, static::$quotes);
    }

    public function test_quotes_route_should_be_protected()
    {
        $response = $this->get('/api/quotes');
        $response->assertStatus(401);
    }

    public function test_should_cache_quotes()
    {
        Cache::shouldReceive('has')
            ->once()
            ->with('quotes')
            ->andReturn(false);

        Cache::shouldReceive('put')
            ->once()
            ->with('quotes', static::$quotes, 3600);

        $response = $this->postJson('/api/token', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $token = $response->json('data.token');

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/quotes');

        $response->assertStatus(200)
            ->assertJson(['data' => Arr::take(static::$quotes, 5)]);
    }


    public function test_should_use_cache()
    {
        Cache::shouldReceive('has')
            ->once()
            ->with('quotes')
            ->andReturn(true);

        Cache::shouldReceive('get')
            ->once()
            ->andReturn(static::$quotes);

        Cache::shouldReceive('put')
            ->never();

        $response = $this->postJson('/api/token', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $token = $response->json('data.token');

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/quotes');

        $response->assertStatus(200)
            ->assertJson(['data' => Arr::take(static::$quotes, 5)]);
    }

    public function test_should_refresh_cache()
    {
        Cache::shouldReceive('has')
            ->once()
            ->with('quotes')
            ->andReturn(true);

        Cache::shouldReceive('put')
            ->once()
            ->with('quotes', static::$quotes, 3600);

        $response = $this->postJson('/api/token', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $token = $response->json('data.token');

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/quotes?refresh=true');

        $response->assertStatus(200)
            ->assertJson(['data' => Arr::take(static::$quotes, 5)]);
    }

}
