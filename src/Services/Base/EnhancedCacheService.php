<?php

namespace Ajz\ApiBase\Services\Base;

use Illuminate\Support\Facades\{Cache,Log,Redis};
use Illuminate\Database\Eloquent\Collection;

final class EnhancedCacheService
{
    protected $prefix;
    protected $defaultTtl;
    protected $version;

    public function __construct(string $prefix = 'api_cache:', int $defaultTtl = 3600)
    {
        $this->prefix = $prefix;
        $this->defaultTtl = $defaultTtl;
        $this->version = config('cache-management.cache_version', 'v1');
    }

    public function remember(string $key, $callback, ?int $ttl = null, array $tags = [])
    {
        $versionedKey = $this->getVersionedKey($key);
        $ttl = $this->getVolatilityBasedTTL($key, $ttl);

        return Cache::tags(array_merge(['api'], $tags))->remember($versionedKey, $ttl, function () use ($callback, $key) {
            $value = $this->preventStampede($key, $callback);
            $this->warmRelatedData($key, $value);
            return $value;
        });
    }

    protected function preventStampede(string $key, $callback)
    {
        $lockKey = "lock:{$key}";
        $lock = Redis::lock($lockKey, 10);

        try {
            if ($lock->get()) {
                return $callback();
            }
            // Wait for other process to finish
            sleep(1);
            return Cache::get($this->getVersionedKey($key)) ?? $callback();
        } finally {
            optional($lock)->release();
        }
    }

    protected function warmRelatedData(string $key, $value)
    {
        if ($value instanceof Collection) {
            foreach ($value as $item) {
                dispatch(new WarmRelatedDataJob($item));
            }
        }
    }

    protected function getVolatilityBasedTTL(string $key, ?int $ttl): int
    {
        if ($ttl !== null) return $ttl;

        // Define TTL based on data volatility patterns
        $volatilityPatterns = [
            'user_' => 3600, // User data: 1 hour
            'product_' => 7200, // Product data: 2 hours
            'config_' => 86400, // Config data: 24 hours
            'static_' => 604800, // Static data: 1 week
        ];

        foreach ($volatilityPatterns as $pattern => $patternTtl) {
            if (strpos($key, $pattern) === 0) {
                return $patternTtl;
            }
        }

        return $this->defaultTtl;
    }

    protected function getVersionedKey(string $key): string
    {
        return "{$this->version}:{$this->prefix}{$key}";
    }

    public function incrementVersion()
    {
        $newVersion = 'v' . (intval(substr($this->version, 1)) + 1);
        config(['cache-management.cache_version' => $newVersion]);
        $this->version = $newVersion;
    }
}
