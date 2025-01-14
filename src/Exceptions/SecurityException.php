<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Exceptions;

use RuntimeException;

class SecurityException extends RuntimeException
{
    public static function invalidSignature(): self
    {
        return new self('Invalid request signature');
    }

    public static function invalidApiKey(): self
    {
        return new self('Invalid or expired API key');
    }

    public static function invalidWebhookSignature(): self
    {
        return new self('Invalid webhook signature');
    }

    public static function missingWebhookSecret(string $identifier): self
    {
        return new self("No webhook secret found for identifier: {$identifier}");
    }

    public static function rateLimitExceeded(int $retryAfter): self
    {
        return new self("Rate limit exceeded. Try again in {$retryAfter} seconds");
    }
}
