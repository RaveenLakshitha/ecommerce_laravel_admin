<?php


// tests/Feature/StatusCodeTest.php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class StatusCodeTest extends TestCase
{
    // use RefreshDatabase;   ← only if you need DB

    /** @test */
    public function it_returns_401_unauthorized()
    {
        $this->getJson('/api/something-protected')
            ->assertUnauthorized();           // 401

        // Or more explicit:
        // ->assertStatus(401);
    }

    /** @test */
    public function it_returns_402_payment_required()
    {
        // Laravel does NOT have built-in 402 logic
        // You must implement it yourself

        // Example route: Route::get('/premium', fn() => abort(402, 'Payment Required'));

        $this->get('/premium')
            ->assertStatus(402)
            ->assertSee('Payment Required');   // optional
    }

    /** @test */
    public function it_returns_403_forbidden()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden();               // 403

        // Alternative ways:
        // abort(403)
        // Gate::denies(...)
        // $this->get(...)->assertStatus(403);
    }

    /** @test */
    public function it_returns_404_not_found()
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound();                // 404

        // Or:
        // $this->get('/users/999999999')->assertNotFound();
    }

    /** @test */
    public function it_returns_419_page_expired_csrf_failure()
    {
        // Important: do NOT use ->withHeaders(['X-CSRF-TOKEN' => '...']) here

        $this->post('/profile/update', [
            'name' => 'New Name',
            // missing _token / X-CSRF-TOKEN
        ])->assertStatus(419);

        // Alternative (very explicit):
        $this->post('/some-post-route', [], [])   // no csrf token headers
            ->assertSessionHasErrors()            // usually also has message
            ->assertStatus(419);
    }

    /** @test */
    public function it_returns_429_too_many_requests()
    {
        $key = 'test-throttle-key:127.0.0.1';

        // Clear previous attempts (clean state)
        RateLimiter::clear($key);

        // Simulate many requests (adjust according to your throttle:60,1 setting)
        for ($i = 0; $i < 60; $i++) {
            $this->get('/api/sensitive-endpoint');
        }

        // The next one should be blocked
        $this->get('/api/sensitive-endpoint')
            ->assertStatus(429)
            ->assertTooManyRequests()
            ->assertHeader('Retry-After');     // usually present
    }

    /** @test */
    public function it_returns_500_internal_server_error()
    {
        // Method 1: force exception in controller
        $this->get('/force-error')           // Route that does: throw new \Exception();
            ->assertStatus(500)
            ->assertInternalServerError();

        // Method 2: division by zero, etc.
        $this->get('/divide-by-zero')        // 1 / 0
            ->assertStatus(500);
    }

    /** @test */
    public function it_returns_503_service_unavailable()
    {
        // Laravel has built-in maintenance mode

        // Option A: artisan down + test
        // But better in pure test:

        // Force maintenance mode programmatically
        \Illuminate\Support\Facades\Artisan::call('down');

        $this->get('/')
            ->assertStatus(503)
            ->assertServiceUnavailable()
            ->assertSee('503');               // or custom message

        // Clean up
        \Illuminate\Support\Facades\Artisan::call('up');
    }
}