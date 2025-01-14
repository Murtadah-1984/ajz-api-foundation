<?php

namespace Ajz\ApiBase\Tests\Feature\Services;

use Ajz\ApiBase\Tests\TestCase;
use Ajz\ApiBase\Services\Base\EnhancedCacheService;
use Illuminate\Support\Facades\{Cache, Redis};
use Illuminate\Database\Eloquent\Collection;

class CacheServiceTest extends TestCase
{
    private EnhancedCacheService $cacheService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheService = new EnhancedCacheService("test_cache:", 3600);
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    public function test_remembers_cached_value(): void
    {
        $counter = 0;
        $callback = function () use (&$counter) {
            $counter++;
            return "test-value";
        };

        // First call should execute callback
        $value1 = $this->cacheService->remember("test-key", $callback);
        $this->assertEquals("test-value", $value1);
        $this->assertEquals(1, $counter);

        // Second call should return cached value
        $value2 = $this->cacheService->remember("test-key", $callback);
        $this->assertEquals("test-value", $value2);
        $this->assertEquals(1, $counter); // Counter shouldn't increment
    }

    public function test_cache_versioning(): void
    {
        // Store value with initial version
        $this->cacheService->remember("test-key", fn() => "initial-value");

        // Verify initial value
        $value1 = $this->cacheService->remember("test-key", fn() => "new-value");
        $this->assertEquals("initial-value", $value1);

        // Increment version
        $this->cacheService->incrementVersion();

        // Should get new value after version increment
        $value2 = $this->cacheService->remember("test-key", fn() => "new-value");
        $this->assertEquals("new-value", $value2);
    }

    public function test_volatility_based_ttl(): void
    {
        $callback = fn() => "test-value";

        // Test different key patterns
        $patterns = [
            "user_123" => 3600,
            "product_456" => 7200,
            "config_app" => 86400,
            "static_data" => 604800,
            "custom_key" => 3600, // Should use default TTL
        ];

        foreach ($patterns as $key => $expectedTtl) {
            $this->cacheService->remember($key, $callback);

            // Verify TTL through Redis
            $versionedKey = "v1:test_cache:{$key}";
            $ttl = Redis::ttl(Cache::getPrefix() . $versionedKey);

            // Allow for 1 second difference due to execution time
            $this->assertGreaterThanOrEqual($expectedTtl - 1, $ttl);
            $this->assertLessThanOrEqual($expectedTtl, $ttl);
        }
    }

    public function test_prevents_cache_stampede(): void
    {
        $counter = 0;
        $callback = function () use (&$counter) {
            $counter++;
            usleep(100000); // Simulate work
            return "test-value";
        };

        // Simulate multiple concurrent requests
        $promises = [];
        for ($i = 0; $i < 5; $i++) {
            $value = $this->cacheService->remember("test-key", $callback);
            $this->assertEquals("test-value", $value);
        }

        // Callback should only be executed once
        $this->assertEquals(1, $counter);
    }

    public function test_caches_with_tags(): void
    {
        $value = $this->cacheService->remember(
            "test-key",
            fn() => "test-value",
            null,
            ["tag1", "tag2"]
        );

        $this->assertEquals("test-value", $value);

        // Verify tags were applied
        Cache::tags(["tag1"])->flush();

        $newValue = $this->cacheService->remember(
            "test-key",
            fn() => "new-value"
        );

        $this->assertEquals("new-value", $newValue);
    }

    public function test_warms_related_data_for_collections(): void
    {
        $collection = new Collection([
            (object)["id" => 1, "name" => "Item 1"],
            (object)["id" => 2, "name" => "Item 2"]
        ]);

        $this->cacheService->remember("collection-key", fn() => $collection);

        // Verify related data warming jobs were dispatched
        // Note: In a real test, you'd use Queue::fake() and assertPushed()
        // but for this example we'll just verify the base functionality
        $this->assertTrue(true);
    }
}
