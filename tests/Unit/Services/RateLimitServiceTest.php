<?php

namespace Ajz\ApiBase\Tests\Unit\Services;

use Ajz\ApiBase\Tests\TestCase;
use Ajz\ApiBase\Services\Base\RateLimitService;
use Ajz\ApiBase\ValueObjects\RateLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, DB};

class RateLimitServiceTest extends TestCase
{
    private RateLimitService $rateLimitService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rateLimitService = new RateLimitService();

        // Seed rate limit tiers
        DB::table("rate_limit_tiers")->insert([
            [
                "name" => "bronze",
                "requests_per_minute" => 60,
                "burst" => 5,
            ],
            [
                "name" => "silver",
                "requests_per_minute" => 300,
                "burst" => 10,
            ],
            [
                "name" => "gold",
                "requests_per_minute" => 1000,
                "burst" => 20,
            ],
        ]);
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    public function test_resolves_default_limit_for_unauthenticated_request(): void
    {
        $request = new Request();
        $rateLimit = $this->rateLimitService->resolveLimit($request, "api");

        $this->assertInstanceOf(RateLimit::class, $rateLimit);
        $this->assertEquals(60, $rateLimit->max);
        $this->assertEquals(0, $rateLimit->used);
        $this->assertEquals(60, $rateLimit->windowSeconds);
    }

    public function test_resolves_tier_specific_limit_for_authenticated_request(): void
    {
        // Create API key
        DB::table("api_keys")->insert([
            "key" => "test-key",
            "tier" => "silver",
            "is_active" => true,
        ]);

        $request = new Request();
        $request->headers->set("X-API-Key", "test-key");

        $rateLimit = $this->rateLimitService->resolveLimit($request, "api");

        $this->assertEquals(300, $rateLimit->max);
        $this->assertEquals(0, $rateLimit->used);
    }

    public function test_increments_usage_counter(): void
    {
        $request = new Request();
        $request->headers->set("X-API-Key", "test-key");

        // First request
        $rateLimit1 = $this->rateLimitService->resolveLimit($request, "api");
        // Second request
        $rateLimit2 = $this->rateLimitService->resolveLimit($request, "api");

        $this->assertEquals(0, $rateLimit1->used);
        $this->assertEquals(1, $rateLimit2->used);
    }

    public function test_caches_rate_limit_config(): void
    {
        DB::table("api_keys")->insert([
            "key" => "test-key",
            "tier" => "gold",
            "is_active" => true,
        ]);

        $config = $this->rateLimitService->getRateLimitConfig("gold");

        $this->assertEquals(1000, $config["requests_per_minute"]);
        $this->assertEquals(20, $config["burst"]);

        // Verify it's cached
        $this->assertTrue(Cache::has("rate_limit_config:gold"));
    }

    public function test_uses_default_config_for_unknown_tier(): void
    {
        $config = $this->rateLimitService->getRateLimitConfig("unknown");

        $this->assertEquals(
            config("api-base.rate-limit.default_limit", 60),
            $config["requests_per_minute"]
        );
        $this->assertEquals(
            config("api-base.rate-limit.default_burst", 5),
            $config["burst"]
        );
    }

    public function test_separate_counters_for_different_types(): void
    {
        $request = new Request();
        $request->headers->set("X-API-Key", "test-key");

        $rateLimitApi = $this->rateLimitService->resolveLimit($request, "api");
        $rateLimitWebhook = $this->rateLimitService->resolveLimit($request, "webhook");

        $this->assertEquals(0, $rateLimitApi->used);
        $this->assertEquals(0, $rateLimitWebhook->used);

        // Make another API request
        $rateLimitApi2 = $this->rateLimitService->resolveLimit($request, "api");
        $rateLimitWebhook2 = $this->rateLimitService->resolveLimit($request, "webhook");

        $this->assertEquals(1, $rateLimitApi2->used);
        $this->assertEquals(1, $rateLimitWebhook2->used);
    }
}
