<?php

declare(strict_types=1);

namespace Ajz\ApiBase\ValueObjects;

final class RateLimit
{
    public function __construct(
        public readonly int $max,
        public readonly int $remaining,
        public readonly int $retryAfter,
        public readonly int $resetsAt
    ) {}

    /**
     * Check if rate limit has been exceeded
     */
    public function exceeded(): bool
    {
        return $this->remaining <= 0;
    }

    /**
     * Create a new rate limit instance
     *
     * @param int $max Maximum requests allowed
     * @param int $used Number of requests used
     * @param int $windowSeconds Time window in seconds
     * @return self
     */
    public static function create(int $max, int $used, int $windowSeconds): self
    {
        $remaining = max(0, $max - $used);
        $now = time();
        $resetsAt = $now + $windowSeconds;
        $retryAfter = $remaining > 0 ? 0 : ($resetsAt - $now);

        return new self(
            max: $max,
            remaining: $remaining,
            retryAfter: $retryAfter,
            resetsAt: $resetsAt
        );
    }

    /**
     * Convert to array
     *
     * @return array{
     *   max: int,
     *   remaining: int,
     *   retry_after: int,
     *   resets_at: int
     * }
     */
    public function toArray(): array
    {
        return [
            'max' => $this->max,
            'remaining' => $this->remaining,
            'retry_after' => $this->retryAfter,
            'resets_at' => $this->resetsAt,
        ];
    }
}
