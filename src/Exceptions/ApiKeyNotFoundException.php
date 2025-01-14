<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Exceptions;

use RuntimeException;

class ApiKeyNotFoundException extends RuntimeException
{
    public static function forKey(string $apiKey): self
    {
        return new self("API key not found: {$apiKey}");
    }
}
