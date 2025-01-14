<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Contracts;

use Illuminate\Http\Request;
use Ajz\ApiBase\ValueObjects\RateLimit;

interface RateLimitServiceInterface
{
    /**
     * Resolve rate limit for request
     *
     * @param Request $request
     * @param string $type
     * @return RateLimit
     */
    public function resolveLimit(Request $request, string $type): RateLimit;

    /**
     * Get rate limit configuration for tier
     *
     * @param string $tier
     * @return array{
     *  requests_per_minute: int,
     *  burst: int
     * }
     */
    public function getRateLimitConfig(string $tier): array;
}
