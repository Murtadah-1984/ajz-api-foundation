<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Services\Base;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, DB};
use Ajz\ApiBase\Contracts\RateLimitServiceInterface;
use Ajz\ApiBase\ValueObjects\RateLimit;

final class RateLimitService implements RateLimitServiceInterface
{
    private const DEFAULT_WINDOW = 60; // 1 minute
    private const CACHE_TTL = 300; // 5 minutes

    /**
     * Resolve rate limit for request
     *
     * @param Request $request
     * @param string $type
     * @return RateLimit
     */
    public function resolveLimit(Request $request, string $type): RateLimit
    {
        $apiKey = $request->header('X-API-Key');
        if (!$apiKey) {
            return $this->getDefaultLimit();
        }

        $config = $this->getRateLimitConfig($this->getTierForApiKey($apiKey));
        $key = "rate_limit:{$apiKey}:{$type}";
        $used = (int) Cache::get($key, 0);

        Cache::increment($key);
        Cache::expire($key, self::DEFAULT_WINDOW);

        return RateLimit::create(
            max: $config['requests_per_minute'],
            used: $used,
            windowSeconds: self::DEFAULT_WINDOW
        );
    }

    /**
     * Get rate limit configuration for tier
     *
     * @param string $tier
     * @return array{requests_per_minute: int, burst: int}
     */
    public function getRateLimitConfig(string $tier): array
    {
        return Cache::remember(
            "rate_limit_config:{$tier}",
            self::CACHE_TTL,
            fn () => $this->fetchTierConfig($tier)
        );
    }

    /**
     * Get default rate limit for unauthenticated requests
     */
    private function getDefaultLimit(): RateLimit
    {
        return RateLimit::create(
            max: config('api-base.rate-limit.default_limit', 60),
            used: 0,
            windowSeconds: self::DEFAULT_WINDOW
        );
    }

    /**
     * Get tier for API key
     */
    private function getTierForApiKey(string $apiKey): string
    {
        return Cache::remember(
            "api_key_tier:{$apiKey}",
            self::CACHE_TTL,
            fn () => DB::table('api_keys')
                ->where('key', $apiKey)
                ->where('is_active', true)
                ->value('tier') ?? 'default'
        );
    }

    /**
     * Fetch tier configuration from database
     *
     * @param string $tier
     * @return array{requests_per_minute: int, burst: int}
     */
    private function fetchTierConfig(string $tier): array
    {
        $config = DB::table('rate_limit_tiers')
            ->where('name', $tier)
            ->first(['requests_per_minute', 'burst']);

        if (!$config) {
            return [
                'requests_per_minute' => config('api-base.rate-limit.default_limit', 60),
                'burst' => config('api-base.rate-limit.default_burst', 5),
            ];
        }

        return [
            'requests_per_minute' => (int) $config->requests_per_minute,
            'burst' => (int) $config->burst,
        ];
    }
}
