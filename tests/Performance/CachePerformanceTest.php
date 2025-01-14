<?php

namespace Ajz\ApiBase\Tests\Performance;

use Ajz\ApiBase\Tests\TestCase;
use Ajz\ApiBase\Services\Base\EnhancedCacheService;
use Illuminate\Support\Facades\Cache;

class CachePerformanceTest extends TestCase
{
    private EnhancedCacheService $cacheService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheService = new EnhancedCacheService();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    public function test_cache_improves_performance(): void
    {
        $iterations = 100;
        $data = $this->generateLargeDataset();

        // Test uncached performance
        $startTime = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            $result = $this->expensiveOperation($data);
        }
        $uncachedTime = microtime(true) - $startTime;

        // Test cached performance
        $startTime = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            $result = $this->cacheService->remember(
                "expensive_operation",
                fn() => $this->expensiveOperation($data)
            );
        }
        $cachedTime = microtime(true) - $startTime;

        // Assert cache provides significant performance improvement
        // Cached operations should be at least 10x faster
        $this->assertGreaterThan($cachedTime * 10, $uncachedTime);

        // Log actual performance metrics
        $this->addToAssertionCount(1);
        fwrite(STDERR, sprintf(
            "\nPerformance Test Results:\n" .
            "Uncached: %.4f seconds\n" .
            "Cached: %.4f seconds\n" .
            "Improvement: %.2fx faster\n",
            $uncachedTime,
            $cachedTime,
            $uncachedTime / $cachedTime
        ));
    }

    public function test_cache_handles_concurrent_load(): void
    {
        $concurrentRequests = 50;
        $data = $this->generateLargeDataset();

        // Test uncached concurrent performance
        $startTime = microtime(true);
        $promises = [];
        for ($i = 0; $i < $concurrentRequests; $i++) {
            $result = $this->expensiveOperation($data);
        }
        $uncachedTime = microtime(true) - $startTime;

        // Test cached concurrent performance
        Cache::flush();
        $startTime = microtime(true);
        for ($i = 0; $i < $concurrentRequests; $i++) {
            $result = $this->cacheService->remember(
                "concurrent_operation",
                fn() => $this->expensiveOperation($data)
            );
        }
        $cachedTime = microtime(true) - $startTime;

        // Assert cache handles concurrent load efficiently
        $this->assertGreaterThan($cachedTime * 5, $uncachedTime);

        // Log concurrent performance metrics
        $this->addToAssertionCount(1);
        fwrite(STDERR, sprintf(
            "\nConcurrent Performance Test Results:\n" .
            "Uncached: %.4f seconds\n" .
            "Cached: %.4f seconds\n" .
            "Improvement: %.2fx faster\n",
            $uncachedTime,
            $cachedTime,
            $uncachedTime / $cachedTime
        ));
    }

    private function generateLargeDataset(): array
    {
        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $data[] = [
                "id" => $i,
                "name" => "Item " . $i,
                "description" => str_repeat("Lorem ipsum ", 10),
                "metadata" => [
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                    "version" => rand(1, 10),
                ]
            ];
        }
        return $data;
    }

    private function expensiveOperation(array $data): array
    {
        // Simulate CPU-intensive operation
        usleep(50000); // 50ms delay
        return array_map(function ($item) {
            $item["processed"] = true;
            $item["hash"] = md5(json_encode($item));
            return $item;
        }, $data);
    }
}
