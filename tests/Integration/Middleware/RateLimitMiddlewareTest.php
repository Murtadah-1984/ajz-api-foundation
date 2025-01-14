<?php

namespace Ajz\ApiBase\Tests\Integration\Middleware;

use Ajz\ApiBase\Tests\TestCase;
use Ajz\ApiBase\Http\Middleware\EnhancedApiRateLimit;
use Ajz\ApiBase\Services\Base\RateLimitService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{Cache, DB};

class RateLimitMiddlewareTest extends TestCase
{
    private EnhancedApiRateLimit $middleware;
    private RateLimitService $rateLimitService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rateLimitService = new RateLimitService();
        $this->middleware = new EnhancedApiRateLimit($this->rateLimitService);

        // Seed rate limit tiers
        DB::table("rate_limit_tiers")->insert([
            [
                "name" => "bronze",
                "requests_per_minute" => 2, // Small limit for testing
                "burst" => 1,
            ]
        ]);

        // Create test API key
        DB::table("api_keys")->insert([
            "key" => "test-key",
            "tier" => "bronze",
            "is_active" => true,
        ]);
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    public function test_adds_rate_limit_headers_to_response(): void
    {
        $request = new Request();
        $request->headers->set("X-API-Key", "test-key");

        $response = $this->middleware->handle($request, function ($req) {
            return new JsonResponse(["data" => "test"]);
        }, "api");

        $this->assertTrue($response->headers->has("X-RateLimit-Limit"));
        $this->assertTrue($response->headers->has("X-RateLimit-Remaining"));
        $this->assertTrue($response->headers->has("X-RateLimit-Reset"));

        $this->assertEquals(2, $response->headers->get("X-RateLimit-Limit"));
        $this->assertEquals(1, $response->headers->get("X-RateLimit-Remaining"));
    }

    public function test_returns_429_when_rate_limit_exceeded(): void
    {
        $request = new Request();
        $request->headers->set("X-API-Key", "test-key");

        // First request (success)
        $response1 = $this->middleware->handle($request, function ($req) {
            return new JsonResponse(["data" => "test"]);
        }, "api");

        // Second request (success)
        $response2 = $this->middleware->handle($request, function ($req) {
            return new JsonResponse(["data" => "test"]);
        }, "api");

        // Third request (should fail)
        $response3 = $this->middleware->handle($request, function ($req) {
            return new JsonResponse(["data" => "test"]);
        }, "api");

        $this->assertEquals(200, $response1->getStatusCode());
        $this->assertEquals(200, $response2->getStatusCode());
        $this->assertEquals(429, $response3->getStatusCode());

        $data = json_decode($response3->getContent(), true);
        $this->assertArrayHasKey("message", $data);
        $this->assertArrayHasKey("retry_after", $data);
        $this->assertEquals("Rate limit exceeded", $data["message"]);
    }

    public function test_different_limits_for_different_types(): void
    {
        $request = new Request();
        $request->headers->set("X-API-Key", "test-key");

        // Make API requests
        $apiResponse1 = $this->middleware->handle($request, function ($req) {
            return new JsonResponse(["data" => "test"]);
        }, "api");

        $apiResponse2 = $this->middleware->handle($request, function ($req) {
            return new JsonResponse(["data" => "test"]);
        }, "api");

        // Make webhook request (should succeed despite API limit being reached)
        $webhookResponse = $this->middleware->handle($request, function ($req) {
            return new JsonResponse(["data" => "test"]);
        }, "webhook");

        $this->assertEquals(200, $apiResponse1->getStatusCode());
        $this->assertEquals(200, $apiResponse2->getStatusCode());
        $this->assertEquals(200, $webhookResponse->getStatusCode());

        // Third API request should fail
        $apiResponse3 = $this->middleware->handle($request, function ($req) {
            return new JsonResponse(["data" => "test"]);
        }, "api");

        $this->assertEquals(429, $apiResponse3->getStatusCode());
    }

    public function test_unauthenticated_requests_use_default_limit(): void
    {
        $request = new Request();

        $response = $this->middleware->handle($request, function ($req) {
            return new JsonResponse(["data" => "test"]);
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            config("api-base.rate-limit.default_limit", 60),
            $response->headers->get("X-RateLimit-Limit")
        );
    }
}
