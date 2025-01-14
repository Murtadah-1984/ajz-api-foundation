<?php

declare(strict_types=1);

namespace App\Domains\Shared\Environment\Exceptions;

use RuntimeException;

final class DomainEnvironmentException extends RuntimeException
{
    public static function domainNotFound(string $domain): self
    {
        return new self("Domain environment file not found for domain: {$domain}");
    }

    public static function invalidEnvironmentFile(string $path): self
    {
        return new self("Invalid environment file at path: {$path}");
    }
}
